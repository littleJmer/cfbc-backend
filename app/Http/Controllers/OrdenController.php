<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Orden;
use App\OrdenCaja;
use App\CajaFlor;

use PDF;
use Excel;
use File;

use App\ColorCode;
use App\FlowerType;
use App\Flower;
use App\Inventory;

class OrdenController extends Controller {

	/**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct(){}

	public function verifyFlowerExistence($flower_type, $variety_color, $name) {

		/**
		 *
		 * Check Catalogs
		 *
		 */
		$flowerType = FlowerType::where('code', $flower_type)->get();
		$color = ColorCode::where('code', $variety_color)->get();

		if( $flowerType->count() === 0 ) {
			$flowerType = FlowerType::create(['code' => $flower_type, 'name' => $flower_type]);
		}
		else{
			$flowerType = $flowerType->first();
		}

		if( $color->count() === 0 ) {
			$color = ColorCode::create(['code' => $variety_color, 'name' => $variety_color]);
		}
		else {
			$color = $color->first();
		}

		/**
		 *
		 * Check flower
		 *
		 */
		$flower = Flower::where('flower_type_id', $flowerType->id)
		->where('variety_color_id', $color->id)->get();

		if($flower->count() === 0) {
			$flower = Flower::create(['flower_type_id' => $flowerType->id, 'variety_color_id' => $color->id]);
		}
		else {
			$flower = $flower->first();
		}

		/**
		 *
		 * Check Inventory
		 *
		 */
		$inventory = Inventory::where("flower_id", $flower->id)->first();

		if($inventory === null) {
			Inventory::create(["flower_id" => $flower->id]);
		}

	}

	public function findOnAssociativeArray($findArray = [], $findKeys = [], $findValues = []) {
		$found_it = false;

		if( 
			$findArray[$findKeys[0]] === $findValues[0] && 
			$findArray[$findKeys[1]] === $findValues[1]
		)
			$found_it = true;

		return $found_it;
	}

	public function bulkOrdersFromCsv($array) {

		foreach ($array as $orden) {
			
			$boxes = $orden["boxes"];
			unset($orden["boxes"]);

			$nueva_orden = Orden::updateOrCreate(
				['sun_valley_order' => $orden['sun_valley_order']], 
				$orden
			);

			$nueva_orden->cajas()->delete();
			foreach($boxes as $box) {

				$flowers = $box["flowers"];
				unset($box["flowers"]);

				$box['orden_id'] = $nueva_orden->id;

				$nueva_caja = OrdenCaja::create($box);
				// $nueva_caja = OrdenCaja::updateOrCreate(
				// 	['orden_id' 	=> $box['orden_id'],
				// 	 'flower_type' 	=> $box['flower_type'],
				// 	 'box_type' 	=> $box['box_type']
				// 	], $box
				// );

				$nueva_caja->flores()->delete();
				foreach ($flowers as $flower) {

					$flower['ordencaja_id'] = $nueva_caja->id;
					
					CajaFlor::create($flower);
					// CajaFlor::updateOrCreate(
					// 	['ordencaja_id' => $nueva_caja->id,
					// 	 'skunumber' 	=> $flower['skunumber']
					// 	],
					// 	$flower
					// );

					$this->verifyFlowerExistence($flower['flower_type'], $flower['variety_color'], $flower['skudesc']);

				}

			}

		}

	}

	/**
	 *
	 * Index view
	 *
	 */
	public function index() {
		return view('app/ordenes/list');
	}

	/*
	 *
	 * Imprimir
	 *
	 */
	public function imprimir($id) {

		$orden 	= $this->get(["ordenid" => [$id]], true);
		$pdf 	= PDF::loadView('app.ordenes.pdf', ['orden' => $orden[0]]);

		$pdf->setPaper('letter', 'landscape');
		$pdf->output();

		$dom_pdf    = $pdf->getDomPDF();
		$canvas     = $dom_pdf ->get_canvas();

		$canvas->page_text(5, 5, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

		return $pdf->stream();
	}

	/*
	 *
	 * Importar
	 *
	 */
	public function importar(Request $request) {

		$orders 		= [];  
		$allowed_ext 	= array("csv");
		$tmp 			= explode(".", $_FILES["file"]["name"]);
		$extension 		= end($tmp);

		if(!in_array($extension, $allowed_ext)) {
			return response()->json(['code'=>400, 'message'=>'El archivo debe ser extensión .CSV'], 200);
			exit(0);
		}

		// logic
		$file_data = fopen($_FILES["file"]["tmp_name"], 'r');

		// trick to skip the first line
		fgetcsv($file_data);

		while($row = fgetcsv($file_data)) {

			// ugly fix
			foreach ($row as $key => $data) {
				$row[$key] = trim(utf8_decode($data));
			}

			/*
			 |
			 | 0 - Customer Name / Acct #
			 | 1 - Customer Ship To
			 | 2 - Customer PO Number
			 | 3 - Sales Rep Name / Number
			 | 4 - Sun Valley Order <-- NOTE: GROUP BY ORDER
			 | 5 - Orig Carrier
			 | 6 - Dest Carrier
			 | 7 - Order Ship Date
			 | 8 - Product Category
			 | 9 - Stem/Bunch
			 | 10 - # of Cases
			 | 11 - Bunches per Box
			 | 12 - Unit Price
			 | 13 - FOB Price
			 | 14 - Box Type < -- NOTE: GROUP BY BOX TYPE
			 | 15 - Box Code / SKU
			 | 16 - UPC Type / Sleeve Name & Size / Insert / Flower Food
			 | 17 - UPC #
			 | 18 - Description
			 | 19 - Date Code
			 | 20 - load date
			 | 21 - bunchQty
			 | 22 - sku <-- NOTE: SKUNUMBER
			 | 23 - sku desc
			 |
			 */

			// skip if does not exists sun valley order
			if(!isset($row[4])) continue;

			/**
			 *
			 * Order
			 *
			 */
			$parts 	= explode('/', $row[4]);

			if( count($parts) !== 2) {
				return response()->json(['code'=>400, 'message'=>'El archivo .CSV no es de Sunvalley.'], 200);
				exit(0);
			}

			$order 	= trim($parts[0]);
			$guess 	= trim($parts[1]);

			// if($order != "750005") continue;

			if(!isset($orders[$order])) {
				$orders[$order]= [
					'customer_name_acct' 	=> $row[0],
					'customer_ship_to' 		=> $row[1],
					'customer_po_number' 	=> $row[2],
					'sales_rep_name' 		=> $row[3],
					'sun_valley_order' 		=> $order,
					'guess' 				=> $guess === 'GUESS',
					'orig_carrier' 			=> $row[5],
					'dest_carrier' 			=> $row[6],
					'order_ship_date' 		=> date('Y-m-d', strtotime($row[7])),
					'load_date' 			=> date('Y-m-d', strtotime($row[20])),
					'date_code' 			=> trim($row[19]),
					'boxes' 				=> []
				];
			}

			// variables
			$box_type 	= $row[14];
			$box_index 	= null;
			$insert 	= false;
			$found_it 	= false;

			$bunch_qty 		= $row[21];
			$skunumber 		= $row[22];
			$location 		= substr($skunumber, 0, 1);
			$flower_type 	= substr($skunumber, 1, 3);
			$variety_color 	= substr($skunumber, 4, 3);
			$grade 			= substr($skunumber, 7, 1);
			$stem_count 	= (int)substr($skunumber, 8, 2);
			$skudesc 		= $row[23];

			$isBuquet 	= $flower_type == "510";
			$open 		= 1;

			/**
			 *
			 * Order -> boxes
			 *
			 */
			$last_index = count($orders[$order]['boxes']) - 1;
			if( count($orders[$order]['boxes']) === 0 || 
				$isBuquet || 
				$orders[$order]['boxes'][$last_index]['open'] === 0) {

				$insert = true;

			}

			if($isBuquet) {
				$open = 0;
			}

			if($insert === true) {

				$stem 				= (int)$row[9];
				$number_of_cases 	= (int)$row[10];
				$bunches_per_box 	= (int)$row[11];

				$orders[$order]['boxes'][] = [
					'box_type' 			=> $box_type,
					'description' 		=> $row[18],
					'upc_number' 		=> $row[17],
					'upc_type' 			=> $row[16],
					'stem_per_bunches' 	=> $stem,
					'bunches_per_box' 	=> $bunches_per_box,
					'number_of_cases' 	=> $number_of_cases,
					'unit_price' 		=> $row[12],
					'fob_price' 		=> $row[13],
					'box_code_sku' 		=> $row[15],
					'flower_type' 		=> $flower_type,
					'open' 				=> $open,
					'flowers' 			=> [],
				];


			}

			$box_index = count($orders[$order]['boxes']) - 1;

			/**
			 *
			 * Order -> boxes -> flowers
			 *
			 */
			$orders[$order]['boxes'][$box_index]['flowers'][] = [
				'skunumber' 	=> $skunumber,
				'bunch_qty' 	=> $bunch_qty,
				'skudesc' 		=> $skudesc,
				'location' 		=> $location,
				'flower_type' 	=> $flower_type,
				'variety_color' => $variety_color,
				'grade' 		=> $grade,
				'stem_count' 	=> $stem_count,
			];

			// revizar la caja
			//
			$score = $orders[$order]['boxes'][$box_index]['bunches_per_box'] * $orders[$order]['boxes'][$box_index]['number_of_cases'];

			$score_saveit = 0;
			foreach ($orders[$order]['boxes'][$box_index]['flowers'] as $flower) {
				$score_saveit += $flower['bunch_qty'];
			}

			if($score_saveit == $score) {
				$orders[$order]['boxes'][$box_index]['open'] = 0;
			}
			else {
				$orders[$order]['boxes'][$box_index]['open'] = 1;
			}
		
		} // end of the loop

		if(count($orders) > 0) {
			$this->bulkOrdersFromCsv($orders);
			return response()->json(['code'=>200, 'message'=>'Se guardarón '.count($orders).' orden(es).'], 200);
		}
		else {
			return response()->json(['code'=> 200, 'message'=>'No se encontrarón ordenes en el archivo.'], 200);
		}

	}

	/*
	 *
	 * Get ordenes
	 *
	 */
	public function get($params = [], $self = false) {

		if($self === false) {
			$status = isset($_GET['status']) ? $_GET['status'] : 1;
			$params["status"] = $status;
		}

		$result 	= Orden::likeCsv($params)->get();
		$data 		= [];
		$ordenes 	= [];

		foreach ($result as $row) {

			$row = $row->toArray();

			unset($row["id"]);

			$ordenid = (int)$row["orden_id"];

			$master_date = date('Y-m-d', (strtotime ( '-3 day' , strtotime ( $row["load_date"] ) ) ));

			if( !isset($data[$ordenid]) ) {

				$split_client = explode("/", $row["customer_name_acct"]);

				$data[$ordenid] = [
					"ordenid" 				=> $ordenid,
					"sun_valley_order" 		=> $row["sun_valley_order"],
					"customer_po_number" 	=> $row["customer_po_number"],
					"sales_rep_name" 		=> $row["sales_rep_name"],
					"orig_carrier" 			=> $row["orig_carrier"],
					"guess" 				=> $row["guess"],
					"ship_date" 			=> date('Y-m-d', strtotime($row["order_ship_date"])),
					"load_date" 			=> date('Y-m-d', strtotime($row["load_date"])),
					"master_date" 			=> $master_date,
					"date_code" 			=> trim($row["date_code"]),
					"destination_via" 		=> $row["dest_carrier"],
					"client" 				=> trim($split_client[0]),
					"acc" 					=> trim($split_client[1]),
					"status" 				=> $row["status"],
					"total_cases" 			=> 0,
					"total_stem" 			=> 0,
					"total_bunches" 		=> 0,
					"array_cases" 			=> [],
					"array_flowers" 		=> [],
					"cases" 	 			=> [],
				];

			}

			// variables
			$box_type 	= $row["box_type"];
			$box_index 	= null;
			$insert 	= false;
			$found_it 	= false;

			$stem 				= (int)$row["stem_per_bunches"];
			$number_of_cases 	= (int)$row["number_of_cases"];
			$bunches_per_box 	= (int)$row["bunches_per_box"];

			$bunch_qty 		= $row["bunch_qty"];
			$skunumber 		= $row["skunumber"];
			$location 		= substr($skunumber, 0, 1);
			$flower_type 	= substr($skunumber, 1, 3);
			$variety_color 	= substr($skunumber, 4, 3);
			$grade 			= substr($skunumber, 7, 1);
			$stem_count 	= (int)substr($skunumber, 8, 2);
			$skudesc 		= $row["skudesc"];

			$isBuquet 	= $flower_type == "510";
			$open 		= 1;

			/**
			 *
			 * Order -> boxes
			 *
			 */
			$last_index = count($data[$ordenid]['cases']) - 1;

			if( count($data[$ordenid]['cases']) === 0 || 
				$isBuquet || 
				$data[$ordenid]['cases'][$last_index]['open'] === 0) {

				$insert = true;
			}

			if($isBuquet) {
				$open = 0;
			}

			if($insert === true) {

				$data[$ordenid]['cases'][] = [
					'orderbox_id' 		=> $row["ordencaja_id"],
					'box_type' 			=> $box_type,
					'description' 		=> $row["description"],
					'upc_number' 		=> $row["upc_number"],
					'upc_type' 			=> $row["upc_type"],
					'stem_per_bunches' 	=> $stem,
					'bunches_per_box' 	=> $bunches_per_box,
					'number_of_cases' 	=> $number_of_cases,
					'unit_price' 		=> $row["unit_price"],
					'fob_price' 		=> $row["fob_price"],
					'box_code_sku' 		=> $row["box_code_sku"],
					'flower_type' 		=> $flower_type,
					'open' 				=> $open,
					'isOpen'			=> $row["open"],
					'flowers' 			=> [],
				];

				$data[$ordenid]['total_cases'] += $number_of_cases;

				if( !in_array($box_type, $data[$ordenid]['array_cases']) ) $data[$ordenid]['array_cases'][] = $box_type;

			}

			$box_index = count($data[$ordenid]['cases']) - 1;

			/**
			 *
			 * Order -> boxes -> flowers
			 *
			 */
			$data[$ordenid]['cases'][$box_index]['flowers'][] = [
				'boxFlower_id' 	=> $row["cajaflor_id"],
				'skunumber' 	=> $skunumber,
				'bunch_qty' 	=> $bunch_qty,
				'skudesc' 		=> $skudesc,
				'location' 		=> $location,
				'flower_type' 	=> $flower_type,
				'variety_color' => $variety_color,
				'grade' 		=> $grade,
				'stem_count' 	=> $stem_count,

				'flower_text' 			=> $row["flower_text"],
				'variety_color_text' 	=> $row["variety_color_text"]
			];

			$data[$ordenid]['total_bunches'] += $bunch_qty;
			$data[$ordenid]['total_stem'] += $stem_count*$bunch_qty;

			if( !in_array($flower_type, $data[$ordenid]['array_flowers']) ) $data[$ordenid]['array_flowers'][] = $flower_type;

			// revizar la caja
			//
			$score = $data[$ordenid]['cases'][$box_index]['bunches_per_box'] * $data[$ordenid]['cases'][$box_index]['number_of_cases'];

			$score_saveit = 0;
			foreach ($data[$ordenid]['cases'][$box_index]['flowers'] as $flower) {
				$score_saveit += $flower['bunch_qty'];
			}

			if($score_saveit == $score) {
				$data[$ordenid]['cases'][$box_index]['open'] = 0;
			}
			else {
				$data[$ordenid]['cases'][$box_index]['open'] = 1;
			}
		
		}

		foreach ($data as $orden) {

			//
			$orden["array_flowers"] = count($orden["array_flowers"]) == 1 ? $orden["array_flowers"][0] : "Variadas";
			//
			$orden["array_cases"] = count($orden["array_cases"]) == 1 ? $orden["array_cases"][0] : "Variadas";

			$ordenes[] = $orden;
		}

		if($self === false)
			return response()->json($ordenes);
		else 
			return $ordenes;
	}

	public function makeExcel(Request $req) {

		$data 		= $req->input('data');
        $fileName 	= time()."_ExplosionXLS";

        $file = Excel::create($fileName, function($excel) use ($data)
        {
            $excel->sheet('Explosion', function($sheet) use ($data)
            {
                $sheet->setOrientation('landscape');

                $sheet->loadView('app.ordenes.excel_explosion',['data' => $data]);
            });

        })->store('xls', 'storage/explocion', true);

 
        return response()->json($file['full']);
	}

	public function recipesOpenOrClose(Request $req) {

		$ids 	= $req->input('data');
		$open 	= $req->input('open');

		$open 	= $open === "true" ? 1 : 0;

		foreach ($ids as $key => $value) { $ids[$key] = (int)$value; }

		// update
		OrdenCaja::whereIn('id', $ids)->update(['open' => $open]);

		return response()->json([$ids, $open]);

	}

	public function recipesSwap($id, Request $req) {

		$id 		= (int)$id;
		$flowers 	= $req->input('flowers');
		$data 		= [];

		// delete old flowers
		CajaFlor::where('ordencaja_id', $id)->delete();

		// insert new flowers
		foreach ($flowers as $f) {
			
			$f['ordencaja_id'] = $id;
			CajaFlor::create($f);
		}

		return response()->json([]);

	}

}






