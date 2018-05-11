<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\FlowerType;
use App\ColorCode;
use App\GradeKey;

class ConfigController extends Controller
{

	public function __construct()
	{
	}

	/*
	 |--------------------------------------------------------------------------
	 | Flower Types
	 |--------------------------------------------------------------------------
	 |
	 |
	 */
	public function show_flowerType()
	{
		return view('app/config/flower-type');
	}

	public function get_flowerType()
	{
		$data = FlowerType::all();
		echo json_encode($data);
	}

	public function store_flowerType(Request $req)
	{
		FlowerType::create($req->input());
		echo json_encode([]);
	}

	public function update_flowerType($flowerType_id, Request $req)
	{
		FlowerType::find($flowerType_id)->update($req->input());
		echo json_encode([]);
	}

	/*
	 |--------------------------------------------------------------------------
	 | Color Codes
	 |--------------------------------------------------------------------------
	 |
	 |
	 */
	public function show_colorCodes()
	{
		return view('app/config/color-codes');
	}

	public function get_colorCodes()
	{
		$data = ColorCode::all();
		echo json_encode($data);
	}

	public function store_colorCodes(Request $req)
	{
		ColorCode::create($req->input());
		echo json_encode([]);
	}

	public function update_colorCodes($colorCode_id, Request $req)
	{
		ColorCode::find($colorCode_id)->update($req->input());
		echo json_encode([]);
	}

	/*
	 |--------------------------------------------------------------------------
	 | Grade Keys
	 |--------------------------------------------------------------------------
	 |
	 |
	 */
	public function show_gradeKey()
	{
		return view('app/config/grade-key');
	}

	public function get_gradeKey()
	{
		$data = GradeKey::all();
		echo json_encode($data);
	}

	public function store_gradeKey(Request $req)
	{
		GradeKey::create($req->input());
		echo json_encode([]);
	}

	public function update_gradeKey($gradeKey_id, Request $req)
	{
		GradeKey::find($gradeKey_id)->update($req->input());
		echo json_encode([]);
	}

}?>