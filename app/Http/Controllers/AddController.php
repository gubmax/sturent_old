<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\AdTags;

class AddController extends Controller
{
  /**
   * Показать форму добавления объявления.
   */
  public function showAdAdd(Request $request)
  {
		if (Auth::guest()) {
			session(['url.intended' => url()->current()]);

      return view('add_for_guest');
		}

    $tags = AdTags::get();

    return view('add', ['tags' => $tags]);
  }
}
