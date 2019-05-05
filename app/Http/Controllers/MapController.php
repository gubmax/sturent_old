<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function showMap(Request $request)
    {
      $location = ProfileController::getCurrentPosition($request);

      if (isset($location->path)) {
        return redirect($location->path);
      }

      return view('map', ['location' => $location]);
    }
}
