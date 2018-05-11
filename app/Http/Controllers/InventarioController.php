<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Inventory;
use App\Flower;
use App\ColorCode;
use App\FlowerType;

class InventarioController extends Controller {

	/**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct() {

	}

	public function index() {

		return view('app/inventario/list');
	}

	public function get() {

		$all = Inventory::listJson()->get();

		return response()->json($all->toArray());
	}

	public function store(Request $req) {

		$quantity = $req->input('quantity');
		$flower_type_id = $req->input('flower_type_id');
		$variety_color_id = $req->input('variety_color_id');

		// check
		$flower = Flower::where('flower_type_id', $flower_type_id)
		->where('variety_color_id', $variety_color_id)->get();

		if( $flower->count() ) {

			return response()->json("Esta flor ya existe.", 400);

		}

		// create
		else {

			$flower = Flower::create([
				'flower_type_id' => $flower_type_id,
				'variety_color_id' => $variety_color_id,
			]);

			Inventory::create(["flower_id" => $flower->id, 'quantity' => $quantity]);

			return response()->json([]);

		}

		
	}

	public function get_para_planificar() {

		$inventario = Inventory::paraPlanificar()->get();

		return response()->json($inventario);

	}

	public function update(Request $req, $id) {
		$quantity = $req->input('quantity');

		Inventory::find($id)->update(['quantity' => $quantity]);

		return response()->json([$id, $quantity]);
	}

	public function get_flower_types() {

		$all = FlowerType::all();

		return response()->json($all->toArray());
	}

	public function get_variety_colors() {

		$all = ColorCode::all();

		return response()->json($all->toArray());
	}


}






