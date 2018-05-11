<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use PDF;
use App;
use Excel;
use File;
use Response;

use App\Order;
use App\ProductOrder;
use App\Recipe;
use App\Flower;
use App\Inventory;
use App\Customer;
use App\Caja;
use App\Item;
use App\Planner;
use App\Especie;
use App\Color;


class HomeController extends Controller
{
    private $response = ["success" => 1, "msg" => "Ok.", "data" => null];
    private $recetas  = [];
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

    public function fechaCompleta($str, $time = true)
    {

        if($str == null || $str == "")
        {
            return "Sin Fecha";
        }

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

      $output = $dia." ".date('d', strtotime($str))." de ".$mes. " del ".$anio;

      if($time) $output .= " a las ".$hora;

      return $output;
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
       //  $all = Flower::all();
       //  $especies = [];

       //  foreach ($all as $flor)
       //  {
       //      $code = trim($flor->especie);
       //      $name = strtoupper(trim($flor->especie));

       //      $code = strtoupper( trim($code[0].$code[2]) );

       //      $now = date("Y-m-d G:h:s");
       //      $especies[$code] = ["code" => $code, "name" => $name, "updated_at" => $now, "created_at" => $now];
       //  }

       // Especie::insert($especies);
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

    public function clientes()
    {
        return view('app/clientes');
    }

    public function flores()
    {
        return view('app/flores');
    }

    public function get_flores()
    {
        $data = ['data' => Flower::all()];
        return response()->json($data);
    }

    public function get_flores_by_id($id)
    {
        $flower = Flower::find($id);
        return response()->json($flower->toArray());
    }

    public function UpdateOrCreate_flores(Request $request)
    {
        $id = (int)$request->input('id');

        $name_posco = $request->input('name_posco');
        $name_campo = $request->input('name_campo');
        $especie    = $request->input('especie');
        $variedad   = $request->input('variedad');
        $color      = $request->input('color');

        if($id > 0)
            $f = Flower::find($id);
        else
            $f = new Flower;

        $f->name_posco  = $name_posco;
        $f->name_campo  = $name_campo;
        $f->especie     = $especie;
        $f->variedad    = $variedad;
        $f->color       = str_replace("_", " ", $color);

        $f->save();

        return response()->json([]);
    }

    public function get_customers()
    {
        $data = ['data' => Customer::all()];
        return response()->json($data);
    }

    public function get_customers_by_id($id)
    {
        $customer = Customer::find($id);
        return response()->json($customer->toArray());
    }

    public function UpdateOrCreate_customers(Request $request)
    {
        $id                 = (int)$request->input('id');
        $name               = $request->input('name');

        if($id > 0)
            $customer = Customer::find($id);
        else
            $customer = new Customer;

        $customer->name = $name;

        $customer->save();

        return response()->json([]);
    }

    public function materiales()
    {
        return view('app/materiales');
    }

    public function get_items()
    {
        $data = ['data' => Item::all()];
        return response()->json($data);
    }

    public function get_items_by_id($itemId)
    {
        $item = Item::find($itemId);
        return response()->json($item->toArray());
    }

    public function UpdateOrCreate_items(Request $request)
    {
        $id                 = (int)$request->input('id');
        $name               = $request->input('name');
        $description        = $request->input('description');
        $size               = $request->input('size');
        $quantity           = (int)$request->input('quantity');
        $price_per_unity    = $request->input('price_per_unity');

        if($id > 0)
            $item = Item::find($id);
        else
            $item = new Item;

        $item->name             = $name;
        $item->description      = $description;
        $item->size             = $size;
        $item->quantity         = $quantity;
        $item->price_per_unity  = (float)$price_per_unity;

        $item->save();

        return response()->json([]);
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
      Order::whereIn("id", $ids)->update(["status" => 4]);
      return response()->json($this->response);
    }

    public function activar_ordenes(Request $request)
    {
      $ids = $request->input('ordenes');
      Order::whereIn("id", $ids)->update(["status" => 1]);
      return response()->json($this->response);
    }

    public function checkRecipes()
    {
      $estatus = ['no_existe' => [], 'repetida' => [], 'ok' => []];

      $order_recipes =
      ProductOrder::distinct()
      ->select('description')
      ->orderBy('description', 'asc')
      ->groupBy('description')
      ->get()
      ->toArray();

      foreach ($order_recipes as $key => $value)
      {
        $description = $value['description'];

        $exists = Recipe::where('name', $description)->get();

        $count = $exists->count();

        if($count == 0)
          $estatus['no_existe'][] = $description;
        else if($count > 1)
          $estatus['repetida'][] = $description;
        else
          $estatus['ok'][] = $description;
      }

      $data = "== [Estatus de Recetas] == V1.0\n\n";

      $data .= "Muestra el estatus de la recetas en base a las ordenes\nque se han importado y a las ordenes que se han registrado\nen el sistema.\n\n";

      foreach ($estatus as $key => $value)
      {
        $data .= "=> $key \n";
        foreach ($value as $description)
        {
          $data .= "    * $description\n";
        }
        $data .= "\n";
      }

      $fileName = time() . '_estatus.txt';
      $path     = public_path('/estatus_recetas/'.$fileName);

      File::put($path, $data);
      return Response::download(public_path('/estatus_recetas/'.$fileName));
    }

    public function planner_save(Request $request)
    {
        $inventario = $request->input('inventario');
        $fechas     = $request->input('fechas');
        $name       = strtoupper(trim($request->input('name')));
        $ordenes  = $request->input('ordenes');

        if($name === "")
        {
            return response()->json([]);
            exit(0);
        }

        $p = new Planner;
        $p->name = $name;
        $p->inventario = json_encode($inventario);

        if( $p->save() )
        {
            foreach ($ordenes as $o)
            {
                $order = Order::find($o['id']);

                $order->planner_id  = $p['id'];
                $order->master_date = $o['master_date'];
                $order->status = 2;

                $order->save();
            }
        }

        return response()->json([$inventario, $fechas, $name, $ordenes]);
    }

    public function planner_dd(Request $request)
    {
        $inventario = $request->input('inventario');
        $fechas     = $request->input('fechas');
        $fileName   = time()."_MasterXLS";
        $solicitar  = [];

        $file = Excel::create($fileName, function($excel) use ($inventario, $fechas)
        {
            $excel->sheet('Master', function($sheet) use ($inventario, $fechas)
            {
                $sheet->setOrientation('landscape');

                $sheet->loadView('excel.master',[
                    'inventario'    => $inventario,
                    'fechas'        => $fechas,
                ]);
            });

            $excel->sheet('Solicitar a Campo', function($sheet) use ($inventario, $fechas)
            {
                $sheet->setOrientation('landscape');

                $sheet->loadView('excel.solicitar',[
                    'inventario'    => $inventario,
                    'fechas'        => $fechas,
                ]);
            });

        })->store('xls', 'storage/master', true);

        $this->response['data'] = $file;
        return response()->json($this->response);
    }

    // se tiene que mejorar esta funcion
    // tomar como referenia planners_by_id
    public function planner_init(Request $request)
    {
        $ids        = $request->input('ordenes');
        $inventario = [];
        $ordenes    = [];

        // obtenemos el inventario al dia
        $inventory = Inventory::all();
        foreach ($inventory as $item)
        {
            $inventario[$item->flower_id] = 
            [
                "fullName" => $item->flower->fullName,
                "quantity" => $item->quantity,
            ];
        }

        // obtenemos las ordenes
        $orders = Order::whereIn("id", $ids)->get();
        foreach ($orders as $orden)
        {
            $ordenes[$orden->id] = [
                "id"                => $orden->id,
                "account"           => $orden->customer_name_acct,
                "ship_date"         => $this->fechaCompleta($orden->order_ship_date, false),
                "load_date"         => $this->fechaCompleta($orden->load_date, false),
                "sun_valley_order"  => $orden->sun_valley_order,
                "destination_via"   => $orden->dest_carrier,
                "recipes"           => [],
                "flowers"           => [],
                "master_date"       => null,
            ];
            $products = $orden->products;

            if($products)
            {
                foreach ($products as $product)
                {
                    $recipe             = $product->recipe;

                    $stem_bunch         = $product->stem_bunch;
                    $no_cases           = $product->no_cases;
                    $bunches_per_box    = $product->bunches_per_box;

                    if($recipe)
                    {
                        $ordenes[$orden->id]["recipes"][$recipe->id] = [
                            'id'                => $recipe->id,
                            'recipe'            => $recipe->name,
                            'type'              => $recipe->typeName,
                            'stem_bunch'        => $stem_bunch,
                            'bounches_per_box'  => $bunches_per_box,
                            'no_cases'          => $no_cases,
                            'box_type'          => $product->box_type,
                            'inmutable'         => $recipe->inmutableDesc,
                            'flowers'           => [],
                        ];
                        $flowers = $recipe->flowers;

                        if($flowers)
                        {
                            foreach ($flowers as $flower)
                            {

                                if( !isset($ordenes[$orden->id]["flowers"][$flower->id]) )
                                {
                                    $ordenes[$orden->id]["flowers"][$flower->id]=
                                    [
                                        "name"      => $flower->name_posco,
                                        "especie"   => $flower->especie,
                                        "variedad"  => $flower->variedad,
                                        "color"     => $flower->color,
                                        "qty"       => 0
                                    ];
                                }

                                // if( !isset($ordenes[$orden->id]["recipes"]["flowers"][$flower->id]) )
                                // {
                                    $ordenes[$orden->id]["recipes"][$recipe->id]["flowers"][$flower->id]=
                                    [
                                        "name"      => $flower->name_posco,
                                        "especie"   => $flower->especie,
                                        "variedad"  => $flower->variedad,
                                        "color"     => $flower->color,
                                        "qty_original" => (int)$flower->pivot->quantity,
                                        "qty"       => 0
                                    ];
                                // }

                                # consolidado surtido
                                if($recipe->type == 2)
                                    $quantity = $stem_bunch * $flower->pivot->quantity * $no_cases;
                                else
                                    $quantity = $flower->pivot->quantity * $bunches_per_box * $no_cases;

                                $ordenes[$orden->id]["flowers"][$flower->id]["qty"]+= $quantity;

                                $ordenes[$orden->id]["recipes"][$recipe->id]["flowers"][$flower->id]["qty"]= $quantity;
                            }
                        }
                    }
                }
            }
        }

        // output
        $this->response["data"] = [
            "inventario"    => $inventario,
            "ordenes"       => $ordenes
        ];

        return response()->json($this->response);
    }

    public function planner_shipping($id)
    {
        $planner    = Planner::find($id);
        $fileName   = time()."_".$planner->name;

        $data = [];
        $actual = null;


        $orders = $planner->orders()->orderBy("order_ship_date", "ASC")->get();
        foreach ($orders as $o)
        {
            $row = ['','','','','','','','',''];

            if($actual != $o->order_ship_date)
            {
                $actual = $o->order_ship_date;
                $row[0] = $o->order_ship_date;
            }

            $row[1] = $o->sun_valley_order;
            $row[2] = $o->customer_name_acct;

            $products = $o->products;

            $first_time = true;
            foreach ($products as $op)
            {
                if(!$first_time)
                {
                    $row = ['','','','','','','','',''];
                }

                $recipe = $op->recipe;
                if($recipe)
                {
                    $row[3] = $recipe->name;
                    $row[4] = $op->stem_bunch;
                    $row[5] = $op->no_cases;
                    $row[6] = $op->bunches_per_box;
                    $row[7] = $op->box_type;
                    $row[8] = (int)$op->stem_bunch * (int)$op->no_cases * (int)$op->bunches_per_box;
                    $data[] = $row;
                }
                else
                {
                    $row[3] = $op->description." - NOT FOUND IT";
                    $row[4] = 0;
                    $row[5] = 0;
                    $row[6] = 0;
                    $row[7] = 0;
                    $row[8] = 0;
                    $data[] = $row;
                }

                $first_time = false;
            }
        }

        Excel::create($fileName, function($excel) use ($data)
        {
            $excel->sheet('Master', function($sheet) use ($data)
            {

                $sheet->setOrientation('landscape');

                $sheet->loadView('excel.shipping',[
                    'data' => $data
                ]);

            });

        })->download('xls');
    }

    public function planners()
    {
        $data = [];

        $planes = Planner::all();

        foreach ($planes as $key => $value)
        {
            $data[] = [
                "id"            => $value->id,
                "name"          => $value->name,
                "status"        => "En progreso",
                "num_orders"    => $value->orders()->count(),
            ];
        }

        $this->response["data"] = $data;
        return response()->json($this->response);
    }

    public function planners_by_id($id)
    {
        $inventario         = [];
        $ordenes            = [];
        $name               = "";

        $planner    = Planner::find($id);
        $name       = $planner->name;
        // $inventario = json_decode($planner->inventario);
        $_ordenes   = $planner->orders;

        // inventario
        // obtenemos el inventario al dia
        $inventory = Inventory::all();
        foreach ($inventory as $item)
        {
            $inventario[$item->flower_id] = 
            [
                "fullName" => $item->flower->fullName,
                "quantity" => $item->quantity,
            ];
        }

        // ordenes
        foreach ($_ordenes as $key => $o)
        {
            $temporal = [
                "id"                => $o->id,
                "account"           => $o->customer_name_acct,
                "ship_date"         => $o->order_ship_date,
                "sun_valley_order"  => $o->sun_valley_order,
                "recipes"           => [],
                "flowers"           => $o->Flowers,
                "master_date"       => $o->master_date,
            ];

            $ordenes[]  = $temporal;
        }

        $this->response["data"] = [
            "inventario"    => $inventario,
            "ordenes"       => $ordenes,
            "name"          => $name,
            // "fechas"        => $fechas,
            "status"        => 1,
        ];

        return response()->json($this->response);
    }

    public function planes()
    {
        return view('app/planes');
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
             * 8 - Product Category
             * 9 - Stem/Bunch
             * 10 - # of Cases
             * 11 - Bunches per Box
             * 12 - Unit Price
             * 13 - FOB Price
             * 14 - Box Type
             * 15 - Box Code / SKU
             * 16 - UPC Type / Sleeve Name & Size / Insert / Flower Food
             * 17 - UPC #
             * 18 - Descrtiption <--- here is a relationship <<< ``` Descrtiption -> recipe ``` >>>
             * 19 - Date Code
             * 20 - load date
             * 21 - bunchQty
             * 22 - sku
             * 23 - sku desc
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
                    'load_date'             => date('Y-m-d', strtotime(trim($row[20]))),
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
                'date_code'             => trim($row[19]),
                'bunchQty'              => trim($row[21]),
                'sku'                   => trim($row[22]),
                'skuDesc'               => trim($row[23]),
            ];

        }

        if(count($orders) > 0)
        {
            // echo json_encode($orders);
            // die(0);
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
            $order = Order::updateOrCreate(
              [
                'sun_valley_order' => $o['sun_valley_order']
              ],
              $o
            );

            $order_id = $order->id;

            foreach ($o['products'] as $p)
            {
              ProductOrder::updateOrCreate(
                [
                  'order_id'      => $order_id,
                  'box_code_sku'  => $p['box_code_sku'],
                  'box_type'      => $p['box_type'],
                  'description'   => $p['description']
                ],
                $p
              );

              $this->recetas[ $p['description'] ] = 1;
            }

            $this->revisar_recetas();
        }
    }

    public function revisar_recetas()
    {
      //print_r($this->recetas);
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

        // listas
        $statusid = 1;
        
        // liberadas
        if($estatus === "liberadas")
        {
          $statusid = 4;
        }
        // planificadas
        else if($estatus === "planificadas")
        {
            $statusid = 2;
        }

        $ordenes = Order::where("status", $statusid)->get();

        $ordenes->transform(function($item, $key)
        {
            $pieces = explode("/", $item->customer_name_acct);

            if(count($pieces) == 2)
            {
                $pieces[0] = trim($pieces[0]);
                $pieces[1] = trim($pieces[1]);
            }

            $tallos     = 0;
            $flor       = [];
            $cajas      = [];
            $n_cajas    = 0;
            $products   = [];

            $fnts       = $item->fnts;
            $flowers    = $fnts['flowers'];
            $boxes      = $fnts['boxes'];

            // echo "<pre>";
            // print_r($fnts);
            // exit(0);

            if(count($flowers) > 0)
            {
                foreach ($flowers as $key => $value)
                {
                    $tallos += (int)$value["qty"];

                    // primeras 2 letras
                    $f_code         = strtoupper($value['name_posco'][0].$value['name_posco'][1]);
                    $flor[$f_code]  = strtoupper(trim($value['especie']));
                }
            }

            if(count($boxes) > 0) 
            {
                foreach ($boxes as $key => $value)
                {
                    $n_cajas += (int)$value["qty"];

                    // sanitizar el nombre de la caja
                    $s_caja = strtoupper(trim($value["type"]));
                    $s_caja = str_replace(["\"", "~", " ", "/", "(", ")"], "", $s_caja);
                    $cajas[$s_caja] = strtoupper(trim($value["type"]));
                }
            }

            $ps = $item->products;
            foreach ($ps as $product)
            {
                $stem_bunch         = $product->stem_bunch;
                $no_cases           = $product->no_cases;
                $bunches_per_box    = $product->bunches_per_box;
                $product_flowers    = [];

                // flower products
                if( isset($product->recipe->flowers) )
                {
                    $recipe     = $product->recipe;
                    $quantity   = 0;

                    foreach($product->recipe->flowers as $flower)
                    {
                        if($recipe->type == 3)
                            $quantity = $stem_bunch * $flower->pivot->quantity * $no_cases;
                        else
                            $quantity = $flower->pivot->quantity * $bunches_per_box * $no_cases;

                        $product_flowers[] = [

                            'name'          => trim($flower->name_posco),
                            'flowers'       => trim($flower->especie),
                            'variedad'      => trim($flower->variedad),
                            'color'         => trim($flower->color),
                            'qty_recipe'    => $flower->pivot->quantity,
                            'qty'           => $quantity,

                        ];
                    }

                }
                //

                $products[] = [
                    'id'                => $product->id,
                    'description'       => $product->description,
                    'stem_bunch'        => $product->stem_bunch,
                    'no_cases'          => $product->no_cases,
                    'bunches_per_box'   => $product->bunches_per_box,
                    'box_type'          => $product->box_type,
                    'flowers'           => $product_flowers,
                ];

            }

            return [
                'id'                => $item->id,
                'sunvalley_order'   => $item->sun_valley_order,
                'ship_date'         => $this->fechaCompleta($item->order_ship_date, false),
                'load_date'         => $this->fechaCompleta($item->load_date, false),
                'destination'       => $item->dest_carrier,
                'status'            => $item->status,
                'client'            => $pieces[0],
                'acc'               => $pieces[1],
                'flor'              => $flor,
                'n_box'             => $n_cajas,
                't_box'             => $cajas,
                'steam'             => $tallos,
                'products'          => $products,
            ];
        });

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

    public function reset_orden($id)
    {
        $o = Order::find($id);
        $o->status          = 1;
        $o->planner_id      = null;
        $o->master_date     = null;
        $o->save();
        return response()->json($this->response);
    }

    /*===============================
    =            RECETAS            =
    ===============================*/

    /**
     * Show the application recipes.
     *
     * @return \Illuminate\Http\Response
     */
    public function recetas()
    {
      return view('app/recetas', [
        'customers' => Customer::orderBy("name", "asc")->get(),
        'cases'     => Caja::orderBy("name", "asc")->get()
      ]);
    }
    
    public function get_recetas()
    {
      $recetas = Recipe::all();

      $recetas->transform(function($o)
      {
        return [
          "id"          => $o->id,
          "name"        => $o->name,
          "case"        => $o->case ? $o->case->name : null,
          "client"      => $o->customer ? $o->customer->name : null,
          "type"        => $o->typeName,
          "inmutable"   => $o->inmutableDesc
        ];
      });

      $this->response["data"] = $recetas;

      return response()->json($this->response);
    }

    public function get_catalogos_recetas()
    {
      $data = array();

      $data["cat_flowers"]    = Flower::orderBy("especie", "asc")->get();
      $data["cat_materiales"] = Item::orderBy("name", "asc")->get();

      $this->response["data"] = $data;
      return response()->json($this->response);
    }

    public function get_recetas_by_id($recetaId)
    {
      $receta = Recipe::find($recetaId);
      $data   = array();

      $data["id"]           = $receta->id;
      $data["name"]         = $receta->name;
      $data["case_id"]      = $receta->case_id;
      $data["customer_id"]  = $receta->customer_id;
      $data["sku"]          = $receta->sku;
      $data["type"]         = $receta->type;

      $data["flowers"]  = $receta->flowers;
      $data["material"] = $receta->material;

      $data["cat_flowers"]    = Flower::orderBy("especie", "asc")->get();
      $data["cat_materiales"] = Item::orderBy("name", "asc")->get();

      $this->response["data"] = $data;
      return response()->json($this->response);
    }

    public function recetas_update($recetaId, Request $request)
    {
      $input              = $request->all();
      $nuevas_flores      = [];
      $nuevos_materiales  = [];

      // receta
      if($recetaId > 0)
      {
        $receta = Recipe::find($recetaId);
        $receta->update($input);
      }
      else
      {
        $receta = Recipe::create($input);
      }

      // flores
      $flores = $input['flowers'];
      foreach ($flores as $flor)
      {
        $nuevas_flores[$flor['id']] = [
          'quantity' => $flor['pivot']['quantity']
        ];
      }
      $receta->flowers()->sync($nuevas_flores);

      // materiales
      $materiales = $input['materiales'];
      foreach ($materiales as $material)
      {
        $nuevos_materiales[$material['id']] = [
          'quantity'        => $material['quantity'],
          'price_per_unity' => $material['price_per_unity']
        ];
      }
      $receta->material()->sync($nuevos_materiales);

      // return
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
