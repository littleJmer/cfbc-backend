<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDF;
use Excel;
use File;

use App\Orden;
use App\OrdenCaja;
use App\CajaFlor;

use App\ColorCode;
use App\FlowerType;
use App\Flower;
use App\Inventory;

use App\Planner;

class PlanController extends Controller {

	/**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct(){}

	public function get() {

		$all = [];

		$all = Planner::all();

		echo $all->toJson();

	}

	public function index() {
		return view('app/v3/produccion/index');
	}

	public function download_plan(Request $req) {


		// echo json_encode($req->input());

		// return;

		$ordenes = Orden::whereIn('id', $req->input())->get();

		// print_r($ordenes->toArray());

		$pdf = PDF::loadView('app.v3.produccion.downloadplan', ['ordenes' => $ordenes]);
		$pdf->setPaper('letter', 'landscape');
		return $pdf->stream('invoice.pdf');
		// return $pdf->download('invoice.pdf');


	}

	public function download_csv(Request $req) {

		$startDate = $req->input("startDate");
		$endDate = $req->input("endDate");

		$corte = $req->input("corte") ? $req->input("corte") : false;
		$inventory = $req->input("inventory");

		// date format
		$start_date = date('Y/m/d', strtotime($startDate."00:00:00"));
		$end_date = date('Y/m/d', strtotime($endDate." 23:59:59"));

		// get required
		$required = $this->getRequired([$start_date, $end_date]);

		// get dates
		$dates = $this->getDates($required);

		// data
		$data = [
			'corte' => $corte,
			'inventory' => $inventory,
			'dates' => $dates,
			'required' => $required
		];

		// excel
		$fileName = time()."_PlanDeProduccionXLS";

		$file = Excel::create($fileName, function($excel) use ($data) {

			$excel->sheet('Explosion', function($sheet) use ($data) {
				$sheet->setOrientation('landscape');

				$sheet->loadView('app.v3.produccion.downloadxls', $data);
			});
		});

		$file = $file->string('xlsx');

		$response =  array(
			'name' => $fileName,
			'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file)
		);

		return response()->json($response);

	}

	/*
	 |--------------------------------------------------------------------------
	 | 
	 |--------------------------------------------------------------------------
	 |
	 | 
	 */
	public function get_produccion() {

		// variables
		$start_date = $_GET['start_date'];
		$end_date = $_GET['end_date'];
		$required = [];
		$dates = [];
		$inventory = [];

		// date format
		$start_date = date('Y/m/d', strtotime($start_date."00:00:00"));
		$end_date = date('Y/m/d', strtotime($end_date." 23:59:59"));

		$required = $this->getRequired([$start_date, $end_date]);

		$dates = $this->getDates($required);

		$inventory = $this->getInventory();

		// return
		echo json_encode([
			'required' => $required,
			'dates' => $dates,
			'inventory' => $inventory,
		]);

	}

	/*
	 |--------------------------------------------------------------------------
	 | 
	 |--------------------------------------------------------------------------
	 |
	 | 
	 */
	public function get_by_id($id) {

		$plan = Planner::find($id);
		$ordenesid = [];

		//
		$o = Orden::select("id")->where("planner_id", $plan->id)->get()->toArray();
		foreach ($o as $value) { $ordenesid[] = $value; }

		$ordenes = app('App\Http\Controllers\OrdenController')->get(["ordenid" => $ordenesid], true);

		$plan->ordenes = $ordenes;

		return response()->json($plan);

	}

	/*
	 |--------------------------------------------------------------------------
	 | 
	 |--------------------------------------------------------------------------
	 |
	 | 
	 */
	function getRequired($range) {

		// catalogos
		$flowerType = [];
		$data = FlowerType::all();
		foreach ($data as $value) {
			$flowerType[$value->code] = $value->name;
		}

		$varietyColor = [];
		$data = ColorCode::all();
		foreach ($data as $value) {
			$varietyColor[$value->code] = $value->name;
		}

		$required = [];

		// find orders
		$orders = Orden::whereBetween('production_date', $range)->orderBy('production_date', 'ASC')->get();

		// logic
		foreach ($orders as $orden) {

			$cajas = $orden->cajas;

			$dateLoop = date('Y-m-d', strtotime($orden->production_date));

			if( !isset($required[$dateLoop]) ) {

				$required[$dateLoop] = [
					'date' => $dateLoop,
					'flowers' => [],
					'box' => 0,
					'stem' => 0,
					'bunch' => 0,
					'ordersid' => []
				];

			}

			$required[$dateLoop]['ordersid'][] = $orden->id;

			foreach ($cajas as $caja) {
				
				$flores = $caja->flores;

				$required[$dateLoop]['box']+=(int)$caja->number_of_cases;

				// $required[$dateLoop]['stem']+= 
				// ((int)$caja->stem_per_bunches*(int)$caja->bunches_per_box)*(int)$caja->number_of_cases;


				foreach ($flores as $flor) {

					if( !isset($required[$dateLoop]['flowers'][$flor->flower_type]) ) {

						$required[$dateLoop]['flowers'][$flor->flower_type] = [
							'desc' => isset($flowerType[$flor->flower_type]) ?
									$flowerType[$flor->flower_type] :
									$flor->flower_type,
							'code' => $flor->flower_type,
							'qty' => 0,
							'variety_color' => [],
						];

					}

					if( !isset($required[$dateLoop]['flowers'][$flor->flower_type]['variety_color'][$flor->variety_color]) ) {

						$required[$dateLoop]['flowers']
						[$flor->flower_type]['variety_color']
						[$flor->variety_color] = [
							'qty' => 0,
							'code' => $flor->variety_color,
							'desc' => isset($varietyColor[$flor->variety_color]) ? 
										$varietyColor[$flor->variety_color] : 
										$flor->variety_color,
						];

					}

					$stemQty = (int)$flor->bunch_qty * (int)$flor->stem_count;

					$required[$dateLoop]['flowers'][$flor->flower_type]['qty'] += $stemQty;

					$required[$dateLoop]['stem']+= $stemQty;
					$required[$dateLoop]['bunch']+= (int)$flor->bunch_qty;
					
					$required[$dateLoop]['flowers']
					[$flor->flower_type]['variety_color']
					[$flor->variety_color]['qty'] += $stemQty;

				}

			}

		}

		return $required;

	}

	/*
	 |--------------------------------------------------------------------------
	 | 
	 |--------------------------------------------------------------------------
	 |
	 | 
	 */
	function getDates($required) {
		$dates = [];

		foreach ($required as $date => $dateInfo) {
			$dates[] = $date;
		}

		return $dates;
	}

	/*
	 |--------------------------------------------------------------------------
	 | 
	 |--------------------------------------------------------------------------
	 |
	 | 
	 */
	function getInventory() {
		$inventory =[];

		// get inventory
		$tt = Inventory::paraPlanificar()->get();
		foreach ($tt as $t) {
			
			if( !isset($inventory[$t->flower_type]) ) {

				$inventory[$t->flower_type] = [
					'qty' => 0,
					'code' => $t->flower_type,
					'desc' => $t->flower_text,
					'variety_color' => [],
				];

			}

			if( !isset($inventory[$t->flower_type]['variety_color'][$t->variety_color]) ) {

				$inventory[$t->flower_type]['variety_color'][$t->variety_color] = [
					'qty' => (int)$t->quantity,
					'code' => $t->variety_color,
					'desc' => $t->variety_color_text,
				];

			}

			$inventory[$t->flower_type]['qty'] += (int)$t->quantity;

		}

		return $inventory;
	}

}






