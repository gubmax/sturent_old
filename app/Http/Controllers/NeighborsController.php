<?php

namespace App\Http\Controllers;

use Auth;
use Response;
Use Image;
use Validator;
use Session;
use App\AdNeighbors;
use App\AdNeighborsImgs;
use App\AdTags;
use App\AdNeighborsHasAdTags;
use App\AdNeighborsRent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class NeighborsController extends Controller
{
 		/**
 		 * Получить страницу Поиск Соседей.
 		 */
 		public function showNeighborsPage(Request $request)
 		{
			// $location = ProfileController::getCurrentPosition($request->location ?? null);
      //
			// if (!$request->has('location') || empty($request->location)) {
			// 	$url= $request->fullUrl();
      //
			// 	if ($request->has('location') && empty($request->location)) {
			// 		$url = preg_replace('*location=?*', 'location='.$location, $url);
      //
			// 		return redirect($url);
			// 	}
      //
			// 	$url_params = substr(strstr($url, '?'), 1, strlen($url));
			// 	$url_params = $url_params ? '&'.$url_params : '';
      //
			// 	return redirect($request->url().'?location='.$location.$url_params);
			// }
      //
			// return view('neighbors.neighbors');
      $location = ProfileController::getCurrentPosition($request);

      if (isset($location->path)) {
        return redirect($location->path);
      }

      return view('neighbors.neighbors', ['location' => $location]);
		}

 		/**
 		 * Получить все объявления.
 		 */
 		public function getAds(Request $request)
 		{
 			$ads = AdNeighbors::with([
				'user' => function ($query) {
					$query->select('id', 'name', 'avatar');
				},
				'imgs' => function ($query) {
					$query->select('ad_id', 'img_name');
				},
        'rent', 'tags'
 			])->where('relevance', '=', '1')
				->orderBy('created_at', 'desc');

 			/*if ($request->has('location') && !empty($request->location)) {
 				$ads = $ads->whereRaw('MATCH (region,city,settlement) AGAINST ("'.$request->location.'")');
 			}*/

      if ($request->has('region') && !empty($request->region)) {
        $ads = $ads->where('region', $request->region);
      }

      if ($request->has('city') && !empty($request->city)) {
        $ads = $ads->where('city', $request->city);
      }

      if ($request->has('settlement') && !empty($request->settlement)) {
        $ads = $ads->where('settlement', $request->settlement);
      }

      if ($request->has('looking') && !empty($request->looking)) {
        $ads = $ads->where('looking', $request->lookin);
      }

      if ($request->has('rent') && !empty($request->rent)) {
				if (count($request->input('rent.*')) > 1)
					$ads = $ads->whereHas('rent', function($query) use ($request) {
						$query->whereIn('ad_neighbors_rent.rent_id', $request->rent);
					});
				else {
					$ads = $ads->whereHas('rent', function($query) use ($request) {
						$query->where('ad_neighbors_rent.rent_id', $request->rent);
					});
				}
			}

      if ($request->has('pay_from') && !is_null($request->pay_from) && is_numeric($request->pay_from)) {
				$ads = $ads->where('pay', '>=', (int) $request->pay_from);
			}

			if ($request->has('pay_to') && !is_null($request->pay_to) && is_numeric($request->pay_to)) {
				$ads = $ads->where('pay', '<=', (int) $request->pay_to);
			}

			if ($request->has('tags') && !empty($request->tags)) {
				if (count($request->input('tags.*')) > 1)
					$ads = $ads->whereDoesntHave('tags', function($query) use ($request) {
						$query->whereIn('ad_neighbors_has_ad_tags.tag_id', $request->tags);
					});
				else {
					$ads = $ads->whereDoesntHave('tags', function($query) use ($request) {
						$query->where('ad_neighbors_has_ad_tags.tag_id', $request->tags);
					});
				}
			}

			if ($request->has('tenants') && is_numeric($request->pay_to)) {
				$ads = $ads->where('tenants', '<=', $request->tenants);
			}

 			if ($request->has('photo')) {
 				$ads = $ads->has('imgs');
 			}

      if ($request->has('count'))
  			$ads = $ads->count();
  		else
  			$ads = $ads->simplePaginate(8);

			return Response::JSON($ads);
 		}

		/**
		 * Получить метки объявлений.
		 */
		public function getCoords(Request $request)
		{
			$this->validate($request, [
				'coords' => 'array|max:4',
				'coords.*' => 'required|numeric'
			]);

			$ads = AdNeighbors::select('ad_id', 'pay', 'latitude', 'longitude')
				->where('latitude', '>', $request->coords[0])
				->where('longitude', '>', $request->coords[1])
				->where('latitude', '<', $request->coords[2])
				->where('longitude', '<', $request->coords[3])
				->where('relevance', '=', '1')
				->get();

			return $ads;
		}

    /**
     * Добавить объявление.
     */
    public function addAd(Request $request)
    {
      if (!$request->ajax()) {
				abort(404);
			}

      $validator = Validator::make($request->all(), [
				'location' => 'required|string|filled',
        'looking' => 'required|numeric',
        'rent' => 'array|max:7',
        'rent.*' => 'required|numeric|min:0|max:7',
				'pay' => 'required|numeric|min:1|digits_between:1,6',
				'tenants' => 'required|numeric|min:1|max:9',
        'text' => 'required|string|filled',
				'tags' => 'array|max:4',
				'tags.*' => 'required|numeric|min:1|max:4'
      ]);

      if ($validator->fails()) {
        return Response::JSON([
          'success' => false,
          'errors' => $validator->errors()
        ]);
      }

			// Dadata.ru api
			$headers = [
			 "Content-type: application/json",
			 "Authorization: Token 541f72d92dd4ccea6f654f5fad08a42b602ca87f",
			 "X-Secret: 6452d37ff171b7ee8741863ae1eb2b69cfc52a8a"
		 ];

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://dadata.ru/api/v2/clean/address');
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, true);
		 	curl_setopt($ch, CURLOPT_POSTFIELDS, '[ "'.$request->location.'" ]');
			$output = curl_exec($ch);
			curl_close($ch);

			$output = json_decode($output);

			if (!$output[0]->result) {
				return false;
				return Response::JSON([
          'success' => false,
          'errors' => ['location' => 'Местоположение не определено.']
        ]);
			}
			// END Dadata.ru api

      $ad_id = abs(crc32(uniqid()));

			AdNeighbors::create([
				'ad_id' => $ad_id,
				'user_id' => Auth::id(),
        'region' => $output[0]->{'region_with_type'},
        'city' => $output[0]->{'city'},
        'settlement' => $output[0]->{'settlement'},
        'looking' => $request->looking,
      	'pay' => $request->pay,
        'tenants' => $request->tenants,
				'text' => $request->text,
			]);

      if ($request->has('rent')) {
        foreach ($request->rent as $rent_id) {
          $rent[] = [
            'ad_id' => $ad_id,
            'rent_id' => $rent_id
          ];
        }
        AdNeighborsRent::insert($rent);
      }

			if ($request->has('tags')) {
				foreach ($request->tags as $tag_id) {
					$tags[] = [
						'ad_id' => $ad_id,
						'tag_id' => $tag_id
					];
				}
				AdNeighborsHasAdTags::insert($tags);
			}
			//$this->swithOffOtherRelevance($ad_id);

      return Response::JSON([
        'success' => true,
        'ad_id' => $ad_id
      ]);
    }

    /**
     * Загрузить картинки к объявлению
     */
    public function uploadImage(Request $request)
    {
      if ($request->ajax() && isset($request->ad_id) && isset($request->src) && !is_array($request->src)) {
          $ad_id = $request->ad_id;
          $ad = AdNeighbors::findOrFail($ad_id);

          if (Auth::id() == $ad->user_id) {
              $countImgs = AdNeighborsImgs::where('ad_id', $ad_id)->count();

              if ($countImgs < 6) {
                  $data = explode(',', $request->src);
                  $encodedData = str_replace(' ', '+', $data[1]);
                  $decodedData = base64_decode($encodedData);

                  $path = public_path().'/images/neighbors/';
                  $fileName = uniqid();

                  $error = [];
                  try {
                    Image::make($decodedData)
                      ->save($path.$fileName.'.jpg')
                      ->fit(320, 180, function ($constraint) {
                        $constraint->upsize();
                      })
                      ->save($path.'thumbnail/'.$fileName.'.jpg');

										if ($countImgs == 0) {
											Image::make($decodedData)
												->fit(16, 9)
												->encode('jpg', 0)
												->save($path.'lazy/'.$fileName.'.jpg');
										}

                    $adImg = new AdNeighborsImgs();

                    $adImg->ad_id = $ad_id;
                    $adImg->img_name = $fileName;

                    $adImg->save();
                  } catch (Exception $e) {
                    $error = $e->getMessage();
                  }

                  return Response::JSON([
                      'upload' => true,
                      'error' => $error
                  ]);
              }

              return Response::JSON([
                  'upload' => false,
                  'error' => 'Вы не можете загружать больше 6 изображений.'
              ]);
          }
      }

      abort(404);
    }

		/**
		 * Получить изображения для объявления о поиске соседа
		 */
		public function getImages($ad_id)
		{
			$imgs = AdNeighborsImgs::select('img_name')
				->where('ad_id', $ad_id)
				->get();
			return $imgs;
		}

    /**
     * Удалить объявление.
     */
    public function delAd($ad_id, Request $request)
    {
        $ad = AdNeighbors::with('imgs')->findOrFail($ad_id);

        if (Auth::id() != $ad->user_id) {
            abort(404);
        }

        $ad->delete();

        if (!empty($ad->imgs[0])):
            $path = public_path().'/images/neighbors/';
            foreach ($ad->imgs as $img):
                if (file_exists($path.$img->img_name.'.jpg')) {
                    unlink($path.$img->img_name.'.jpg');
                }
                if (file_exists($path.$img->img_name.'_thumbnail.jpg')) {
                    unlink($path.$img->img_name.'_thumbnail.jpg');
                }
            endforeach;
        endif;

				if ($request->ajax())
					return Response::JSON([
						'success' => true,
					]);

        return redirect('/profile');
    }

    /**
     * Изменить актуальность объявления.
     */
    public function swithOnRelevance($ad_id, Request $request)
    {
        $ad = AdNeighbors::findOrFail($ad_id);

        if (Auth::id() == $ad->user_id) {
            if ($ad->relevance == 1)
                $ad->relevance = 0;
            else {
                $ad->relevance = 1;
								$this->swithOffOtherRelevance($ad_id);
						}

            $ad->save();
        }
        else
            abort(404);

        if ($request->ajax()) {
        	return Response::JSON([
            'success' => true,
          ]);
        }

        return redirect('/profile');
    }

		/**
		 * Убрать актуальность всех объявлений, кроме указанного.
		 */
		private function swithOffOtherRelevance($ad_id)
		{
			AdNeighbors::where('user_id', Auth::id())
				->where('ad_id', '!=', $ad_id)
				->update(['relevance' => 0]);
		}
}
