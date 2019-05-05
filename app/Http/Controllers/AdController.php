<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdNeighbors;
use App\AdShare;
use Auth;

class AdController extends Controller
{
  /**
   * Отобразить конкретное объявление.
   */
  public function showAd(Request $request)
  {
    $this->validate($request, [
      'neighbors' => 'sometimes|nullable',
      'share' => 'sometimes|nullable',
      'id' => 'required|numeric',
    ]);

    if ($request->has('neighbors') && $request->has('id')) {
      $ad = new AdNeighbors;
      $ad = $ad->with(['rent']);
      $page = 'neighbors';
    }
    elseif ($request->has('share')) {
      $ad = new AdShare;
      $page = 'share';
    }
    else abort(404);

    $ad = $ad->with([
      'user' => function ($query) {
        $query->select('id', 'name', 'avatar');
      },
      'imgs' => function ($query) {
        $query->select('ad_id', 'img_name');
      },
      'tags'
    ])->findOrFail($request->id);

    return view('ad', ['ad' => $ad, 'page' => $page]);
  }

  /**
   * Изменить объявление.
   */
  public function editAdNeighbors($ad_id)
  {
    $ad = AdNeighbors::findOrFail($ad_id);
    return $this->editAd($ad, 'neighbors');
  }

  public function editAdShare($ad_id)
  {
    $ad = AdShare::findOrFail($ad_id);
    return $this->editAd($ad, 'share');
  }

  private function editAd($ad, $page)
  {
    if (Auth::id() == $ad->user_id) {
      return view('ad_edit', ['ad' => $ad, 'page' => $page]);
    }
    else
      abort(404);
  }

  /**
   * Сохранить изменения в объявлении.
   */
  public function saveAdNeighbors($ad_id, Request $request)
  {
    $ad = AdNeighbors::findOrFail($ad_id);
    return $this->saveAd($ad, $ad_id, 'neighbors', $request);
  }

  public function saveAdShare($ad_id, Request $request)
  {
    $ad = AdShare::findOrFail($ad_id);
    return $this->saveAd($ad, $ad_id, 'share', $request);
  }

  private function saveAd($ad, $ad_id, $page, $request)
  {
    $this->validate($request, [
      'text' => 'required|string|filled'
    ]);

    if (Auth::id() != $ad->user_id) {
      abort(404);
    }

    $ad->text = $request->text;
    $ad->save();

    return redirect('/ad?'.$page.'&id='.$ad_id);
  }
}
