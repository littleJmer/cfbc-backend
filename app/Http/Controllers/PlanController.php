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

}






