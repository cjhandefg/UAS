<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MapDataController extends Controller
{
  public function index()
  {
    return view('interactive');
  }

  public function getMarkers()
  {
    return response()->json(Marker::all());
  }

  public function storeMarker(Request $request)
  {
    $marker = Marker::create($request->all());
    return response()->json($marker);
  }

}