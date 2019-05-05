<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Response;
use DB;
use App\AdNeighbors;
use App\AdNeighborsFavorites;
use App\AdShare;
use App\AdShareFavorites;

class FavoritesController extends Controller
{
  private function getPage(Request $request, $table) {
    $this->validate($request, [
      'neighbors' => 'sometimes|nullable',
      'share' => 'sometimes|nullable',
      'count' => 'sometimes|nullable'
    ]);

    if ($request->has('neighbors')) {
      if ($table == 1)
        $ad = new AdNeighborsFavorites;
      else {
        $ad = new AdNeighbors;
        $ad = $ad->with(['rent']);
      }

      $table_name = 'neighbors';
    }
    elseif ($request->has('share')) {
      if ($table == 1)
        $ad = new AdShareFavorites;
      else
        $ad = new AdShare;

      $table_name = 'share';
    }

    return isset($ad, $table_name) ? [$ad, $table_name] : null;
  }

  public function getNeighborsFavorites(Request $request) {
    $ad = new AdNeighbors;

    return $this->getFavorites([$ad, 'neighbors', $request->has('count')]);
  }

  public function getShareFavorites(Request $request) {
    $ad = new AdShare;

    return $this->getFavorites([$ad, 'share', $request->has('count')]);
  }

  private function getFavorites($page)
  {
    $ads = $page[0];
    $table_name = $page[1];

    $favorites_id = Session::has('favorites_id.'.$table_name) ? Session::get('favorites_id.'.$table_name) : null;

    if (empty($favorites_id)) {
      return null;
    }

    $ads = $ads->with([
      'user' => function ($query) {
        $query->select('id', 'name', 'avatar');
      },
      'imgs' => function ($query) {
        $query->select('ad_id', 'img_name');
      },
      'tags'
    ])
    ->whereIn('ad_'.$table_name.'.ad_id', $favorites_id)
    ->where('relevance', '=', '1')
    ->orderBy('created_at', 'desc');

		if ($page[2]) {
			$ads = $ads->count();
		}
		else {
			$ads = $ads->simplePaginate(8);
		}

  	return Response::JSON($ads);
  }

  public function switchFavorites($ad_id, Request $request)
  {
    $page = $this->getPage($request, 1);

    if (!Session::has('favorites_id.'.$page[1]) || !in_array($ad_id, Session::get('favorites_id.'.$page[1]))) {
			//Добавление в Избранное
      $favorites_count = Session::has('favorites_count') ? Session::get('favorites_count') + 1 : 1;

      Session::put('favorites_count', $favorites_count);

      Session::push('favorites_id.'.$page[1], $ad_id);

			if (Auth::check()) {
				$page[0]->create([
					'user_id' => Auth::id(),
					'ad_id' =>  $ad_id
				]);
			}

      return Response::JSON([
				'check' => Session::get('favorites_count'),
        'favorites_count' => $favorites_count
      ]);
    }
		elseif (Session::has('favorites_id.'.$page[1]) && in_array($ad_id, Session::get('favorites_id.'.$page[1]))) {
			//Удаление из Избранного
			$favorites_count = Session::has('favorites_count') ? Session::get('favorites_count') - 1 : 0;
			Session::put('favorites_count', $favorites_count);

			$key = array_search($ad_id, Session::get('favorites_id.'.$page[1]));
			Session::forget('favorites_id.'.$page[1].'.'.$key);

      if (Auth::check()) {
				$page[0]->where('user_id', Auth::id())
					->where('ad_id', $ad_id)
					->delete();
			}
			return Response::JSON([
				'favorites_count' => $favorites_count
			]);
		}
  }

	public function clearFavorites(Request $request)
	{
    $page = $this->getPage($request, 1);

		Session::forget(['favorites_id.'.$page[1], 'favorites_count']);

		if (Auth::check()) {
			$page[0]->where('user_id', Auth::id())
				->delete();
		}

		return Response::JSON([
			'success' => true
		]);
	}

	public static function setFavorites()
	{
    $favorites_neighbors_id = Session::has('favorites_id.neighbors') ? Session::get('favorites_id.neighbors') : null;
    $favorites_share_id  = Session::has('favorites_id.share') ? Session::get('favorites_id.share') : null;

		// Если в избранном были объявления до атворизации, то добавляем их пользователю.
		function addFavorites($favorites_id, $table_name, $table) {
      $save_ids = [];

      if (!empty($favorites_id)) {
        $table = $table->select('ad_id')
          ->where('user_id', Auth::id())
          ->whereIn('ad_id', $favorites_id)
          ->get()
          ->toArray();

        $favorites_id = array_values(array_diff($favorites_id, $table));

        foreach ($favorites_id as $ad_id) {
          $save_ids[] = '('.$ad_id.','.Auth::id().')';
        }
      }

			if (count($save_ids) > 0) {
        DB::insert('insert ignore into '.$table_name.' (ad_id,user_id) values '.implode(',', $save_ids));
				//$check_favorites->insert($save_ids);
			}
		}

    addFavorites($favorites_neighbors_id, 'ad_neighbors_favorites', new AdNeighbors);
    addFavorites($favorites_share_id, 'ad_share_favorites', new AdShare);

    $favorites_neighbors_id = AdNeighborsFavorites::where('user_id', Auth::id())->get();
    $favorites_share_id = AdShareFavorites::where('user_id', Auth::id())->get();

		Session::put('favorites_count', $favorites_neighbors_id->count() + $favorites_share_id->count());
		Session::forget('favorites_id.neighbors');
    Session::forget('favorites_id.share');

		if ($favorites_neighbors_id->count()) {
			foreach ($favorites_neighbors_id as $key => $value) {
				Session::push('favorites_id.neighbors', $value['ad_id']);
			}
		}
    if ($favorites_share_id->count()) {
      foreach ($favorites_share_id as $key => $value) {
        Session::push('favorites_id.share', $value['ad_id']);
      }
    }

		return true;
	}
}
