<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use PDF;
use App;
use Excel;

use App\Order;
use App\ProductOrder;
use App\Recipe;
use App\Inventory;

class HomeController extends Controller
{
    private $response = ["success" => 1, "msg" => "Ok.", "data" => null];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if( !App::environment('local') )
        {
            $this->middleware('auth');
        }
        else
        {
            // Auth::logout();
            Auth::attempt(['email' => 'admin@email.com', 'password' => 'abc123']);
        }
    }

    public function fechaCompleta($str)
    {
      $dias = array(
        "Domingo", "Lunes",
        "Martes", "Miercoles",
        "Jueves", "Viernes", "Sábado"
      );

      $meses = array(
        "Enero", "Febrero", "Marzo",
        "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre",
        "Octubre", "Noviembre", "Diciembre"
      );

      $dia  = $dias[ date('w', strtotime($str)) ];
      $mes  = $meses[ date('n', strtotime($str))-1 ];
      $anio = date('Y', strtotime($str));
      $hora = date('h:i:s A', strtotime($str));

      return $dia." ".date('d')." de ".$mes. " del ".$anio." a las ".$hora;
      // Salida: Viernes 24 de Febrero del 2012 a las 2:35 am
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app/index');
        // /home/o3hp2v4rhlew
    // symlink('home/o3hp2v4rhlew/laravel/storage/app/public','home/o3hp2v4rhlew/public_html/storage');
    }

    /**
     * Show the application recipes.
     *
     * @return \Illuminate\Http\Response
     */
    public function recetas()
    {
        return view('app/recetas');
    }

    /**
     * Show the application almacen.
     *
     * @return \Illuminate\Http\Response
     */
    public function almacen()
    {
        return view('app/almacen');
    }

    public function askForUser()
    {
        if (Auth::check())
        {
            $user = Auth::user();
            return response()->json($user);
        }
        else
        {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
    }

    /**
     * Print a file with an order.
     *
     * @return \Illuminate\Http\Response
     */
    public function imprimir_orden($orderId)
    {
        $order  = Order::find($orderId);
        $pdf    = PDF::loadView('pdf.orden', ['order' => $order]);

        //
        $pdf->setPaper('letter', 'landscape');
        $pdf->output();
        $dom_pdf    = $pdf->getDomPDF();
        $canvas     = $dom_pdf ->get_canvas();
        $canvas->page_text(5, 5, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        //
        return $pdf->stream();
    }

    public function imprimir_master_orden($orderId)
    {
        $order  = Order::find($orderId);
        $pdf    = PDF::loadView('pdf.master', ['order' => $order]);

        //
        $pdf->setPaper('letter', 'landscape');
        $pdf->output();
        $dom_pdf    = $pdf->getDomPDF();
        $canvas     = $dom_pdf ->get_canvas();
        $canvas->page_text(5, 5, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        //
        return $pdf->stream();
    }

    public function liberar_ordenes(Request $request)
    {
      $ids = $request->input('ordenes');
      Order::whereIn("id", $ids)->update(["status" => 2]);
      return response()->json($this->response);
    }

    public function master(Request $request)
    {
        $fileName   = time()."_Master";
        $ids        = $request->input('ordenes');
        $flores = [];

        // inventario
        $inventario = Inventory::all();
        foreach ($inventario as $item)
        {
            $flores[$item->flower_id] = 
            [
                $item->flower->fullName,
                // inventario
                $item->quantity,
                // requerido
                0,
                // corte
                0,
                // desecho
                0
            ];
        }

        // ordenes
        $ordenes = Order::whereIn("id", $ids)->where("status", 1)->get();

        foreach ($ordenes as $orden)
        {
            $products = $orden->products;
            if($products)
            {
                foreach ($products as $product)
                {
                    $recipe = $product->recipe;
                    $no     = $product->no_cases;
                    $per    = $product->bunches_per_box;
                    if($recipe)
                    {
                        $flowers = $recipe->flowers;
                        if($flowers)
                        {
                            foreach ($flowers as $flower)
                            {
                                // requerido
                                $flores[$flower->id][2] += (int)$flower->pivot->quantity * $no * $per;
                                
                                // inv. final
                                $flores[$flower->id][5] = 
                                $flores[$flower->id][1] - $flores[$flower->id][2];
                            }
                        }
                    }
                }
            }
        }

        $headers = ['Flor', 'Inv.', 'Req.', 'Corte', 'Desecho', 'Inv. Final'];
        $data =
        [
            'headers'       => $headers,
            'flores'        => $flores
        ];

        $file = Excel::create($fileName, function($excel) use ($data)
        {
            $excel->sheet('Master', function($sheet) use ($data)
            {
                $sheet->setOrientation('landscape');

                //
                $sheet->row(3, $data['headers']);

                //
                $row = 4;
                foreach ($data['flores'] as $flor)
                {
                    $sheet->row($row, $flor);
                    $row++;
                }

            });

        })->store('xls', 'storage/master', true);

        $this->response['data'] = $file;
        return response()->json($this->response);
    }

    /**
     * Import a file with orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function importar_ordenes(Request $request)
    {
        $response = ['success' => 1, 'msg' => 'ok.', 'data' => null];

        $orders         = [];
        $allowed_ext    = array("csv");
        $tmp            = explode(".", $_FILES["file"]["name"]);
        $extension      = end($tmp);

        if(!in_array($extension, $allowed_ext))
        {
            $response['success']    = 0;
            $response['msg']        = 'El archivo a importar debe ser .csv';
            return response()->json($response);
            exit(0);
        }

        $file_data = fopen($_FILES["file"]["tmp_name"], 'r');
        fgetcsv($file_data);
        while($row = fgetcsv($file_data))
        {
            // ugly fix
            foreach ($row as $key => $data)
            {
                $row[$key] = utf8_decode($data);
            }

            /**
             *
             * 0 - Customer Name / Acct #
             * 1 - Customer Ship To
             * 2 - Customer PO Number
             * 3 - Sales Rep Name / Number
             * 4 - Sun Valley Order <--- this is the order <<< ``group by``` >>>
             * 5 - Orig Carrier
             * 6 - Dest Carrier
             * 7 - Order Ship Date
             * 8 - Product Category <--- here is a relationship <<< ``Product Category``` >>>
             * 9 - Stem/Bunch
             * 10 - # of Cases
             * 11 - Bunches per Box
             * 12 - Unit Price
             * 13 - FOB Price
             * 14 - Box Type
             * 15 - Box Code / SKU
             * 16 - UPC Type / Sleeve Name & Size / Insert / Flower Food
             * 17 - UPC #
             * 18 - Descrtiption
             * 19 - Date Code
             *
             **/

            if(!isset($row[4]))
            {
                // skip
                continue;
            }

            $partes = explode('/', $row[4]);
            $orden  = trim($partes[0]);

            if(!isset($orders[$orden]))
            {
                $orders[$orden]=
                [
                    'customer_name_acct'    => trim($row[0]),
                    'customer_ship_to'      => trim($row[1]),
                    'customer_po_number'    => trim($row[2]),
                    'sales_rep_name'        => trim($row[3]),
                    'sun_valley_order'      => trim($row[4]),
                    'orig_carrier'          => trim($row[5]),
                    'dest_carrier'          => trim($row[6]),
                    'order_ship_date'       => date('Y-m-d', strtotime(trim($row[7]))),
                    'products'              => []
                ];
            }

            $orders[$orden]['products'][]=
            [
                'product_category'      => trim($row[8]),
                'stem_bunch'            => trim($row[9]),
                'no_cases'              => trim($row[10]),
                'bunches_per_box'       => trim($row[11]),
                'unit_price'            => trim($row[12]),
                'fob_price'             => trim($row[13]),
                'box_type'              => trim($row[14]),
                'box_code_sku'          => trim($row[15]),
                'upc_type'              => trim($row[16]),
                'upc_no'                => trim($row[17]),
                'description'           => trim($row[18]),
                'date_code'             => trim($row[19])
            ];

        }

        if(count($orders) > 0)
        {
          $this->saveOrders($orders);
        }

        $response['msg']    = 'Se importaron con existo las ordenes.';
        $response['data']   = $orders;
        return response()->json($response);
    }

    public function saveOrders($orders)
    {
        foreach ($orders as $o)
        {
            // $order = Order::create($o);

            // foreach ($o['products'] as $p)
            // {
            //     $order->products()->save(new ProductOrder($p));
            // }

            $order = Order::updateOrCreate(
              // keys
              [
                'sun_valley_order' => $o['sun_valley_order']
              ],
              // data
              $o
            );

            $order_id = $order->id;

            foreach ($o['products'] as $p)
            {
              ProductOrder::updateOrCreate(
                // keys
                [
                  'order_id'      => $order_id,
                  'box_code_sku'  => $p['box_code_sku'],
                  'box_type'      => $p['box_type'],
                  'description'   => $p['description']
                ],
                // data
                $p
              );
            }
        }
    }

    public function get_ordenes(Request $request)
    {
        $response=
        [
            'success'   => 1,
            'msg'       => 'ok.',
            'data'      => [],
        ];

        $estatus  = $request->input("estatus");
        $statusid = 1;
        
        if($estatus === "liberadas")
        {
          $statusid = 2;
        }

        $ordenes = Order::where("status", $statusid)->get();

        $response['data'] = $ordenes;

        return response()->json($response);
    }

    public function get_orden($ordenId)
    {
      $orden    = Order::find($ordenId);
      $log      = array();
      $products = array();

      // (1) orden log
      foreach($orden->revisionHistory as $history)
      {
        if($history->key === "created_at" )
        {
          $h =
          $this->fechaCompleta($history->created_at)." - ".
          $history->userResponsible()->name.
          " Creo la orden.";
        }
        else
        {
          $h =
          $this->fechaCompleta($history->created_at)." - ".
          $history->userResponsible()->name." Cambio el campo ".
          $history->fieldName()." de ".$history->oldValue()." a ".
          $history->newValue().".";
        }

        $log[] = ["time" => $history->created_at, "text" => $h];

      }

      // (2) product log
      $ps = $orden->products;
      foreach ($ps as $product)
      {

        $products[] = $product->toArray();

        foreach ($product->revisionHistory as $history)
        {
          $item = "";
          $code = $history->revisionable_type."::find(".$history->revisionable_id.");";
          eval("\$item = $code;");

          $h =
          $this->fechaCompleta($history->created_at)." - ".
          "[".$item->box_code_sku."] ".
          $history->userResponsible()->name." Cambio el campo ".
          $history->fieldName()." de ".$history->oldValue()." a ".
          $history->newValue().".";

          $log[] = ["time" => $history->created_at, "text" => $h];

        }
      }

      $data = [
        'log'       => $log,
        'products'  => $products,
        'orden' =>
        [
          'customer_name_acct'  => $orden->customer_name_acct,
          'customer_po_number'  => $orden->customer_po_number,
          'sales_rep_name'      => $orden->sales_rep_name,
          'sun_valley_order'    => $orden->sun_valley_order
        ]
      ];

      $this->response["data"] = $data;
      return response()->json($this->response);
    }

    /*===============================
    =            RECETAS            =
    ===============================*/
    
    public function get_recetas()
    {
      $recetas = Recipe::all();

      $recetas->transform(function($o)
      {
        return [
          "id"      => $o->id,
          "name"    => $o->name,
          "cases"   => $o->cases,
          "client"  => $o->client,
        ];
      });

      $this->response["data"] = $recetas;

      return response()->json($this->response);
    }

    public function get_recetas_by_id($recetaId)
    {
      $receta = Recipe::find($recetaId);
      $data   = array();

      $data["id"]     = $receta->id;
      $data["name"]   = $receta->name;
      $data["cases"]  = $receta->cases;
      $data["client"] = $receta->client;
      $data["sku"]    = $receta->sku;

      $data["items"]  = $receta->itemsObj;

      $this->response["data"] = $data;
      return response()->json($this->response);
    }

    public function recetas_update($recetaId, Request $request)
    {
      $input = $request->all();
      Recipe::find($recetaId)->update($input);
      return response()->json($this->response);
    }
    
    /*=====  End of RECETAS  ======*/

    public function get_inventario()
    {
        $inventory = Inventory::all();

        $inventory->transform(function($o)
        {
            return [
                "id"            => $o->id,
                "flower_id"     => $o->flower_id,
                "quantity"      => $o->quantity,
                "name_posco"    => $o->flower->name_posco,
                "name_campo"    => $o->flower->name_campo,
                "especie"       => $o->flower->especie,
                "color"         => $o->flower->color,
                "variedad"      => $o->flower->variedad
            ];
        });

        $this->response["data"] = $inventory;
        return response()->json($this->response);
    }

    public function editar_inventario($inventarioId, Request $request)
    {
        $input = $request->all();

        Inventory::find($inventarioId)->update($input);
        return response()->json($input);
    }
    
}
