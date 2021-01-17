<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Subsection;
use Illuminate\Http\Request;

class ApiController extends Controller {
    public function getSubsections(Request $request) {
        return response()->json(Subsection::where('rubrics_id', $request->post('id'))->get());
    }

    public function getCities(Request $request) {
        return response()->json(City::where('country_id', $request->post('id'))->get());
    }
}
