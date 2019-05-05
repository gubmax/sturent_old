<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Session;
use File;
use Image;
use App\User;
use App\AdNeighbors;
use App\AdShare;
use App\ContactData;
use App\UsersHasContactData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class ProfileController extends Controller
{
  /**
   * Show the profile for the given user.
   */
  public function showProfile($id = null)
  {
    if ($id == null) {
      if (Auth::guest()) {
        Session::flash('info', 'Необходимо авторизоваться на сайте');
        return redirect('/');
      }
      else
        $user = Auth::user();
        $id = $user->id;
    }
    else
      $user = User::findOrFail($id);

    return view('profile/profile', ['user' => $user]);
  }

  public function getProfileNeighborsAds($id = null, Request $request)
  {
    $ads = new AdNeighbors;
    $ads = $ads->with(['rent']);
    return $this->getProfileAds($ads, $id, $request);
  }

  public function getProfileShareAds($id = null, Request $request)
  {
    $ads = new AdShare;
    return $this->getProfileAds($ads, $id, $request);
  }

	private function getProfileAds($ads, $id, $request)
	{
    $this->validate($request, [
      'count' => 'sometimes|nullable'
    ]);

		$ads = $ads->with([
			'user' => function ($query) {
				$query->select('id', 'name', 'avatar');
			},
			'imgs' => function ($query) {
				$query->select('ad_id', 'img_name');
			},
			'tags'
		])->where('user_id', $id)
			->orderBy('created_at', 'desc');

		if ($id != Auth::id()) {
			$ads = $ads->where('relevance', '=', '1');
		}

		if ($request->has('count')) {
			$ads = $ads->count();
		}
		else {
			$ads = $ads->simplePaginate(8);
		}

		return $ads;
	}

  public function editProfile()
  {
		$id = Auth::id();

		$user = User::with([
			'contactData' => function ($query) {
				$query->orderBy('contact_data_id');
			},
		])->findOrFail($id);

		$contact_data_list = ContactData::all();

    return view('profile/edit', ['user' => $user, 'contact_data_list' => $contact_data_list]);
  }

  public function saveProfile(Request $request)
  {
    $this->validate($request, [
      'name' => 'required|string|filled|max:45',
      'avatar' => 'mimes:jpg,jpeg,bmp,png|max:1024',
			'contact-data' => 'array|max:3',
			'contact-data.*' => 'max:255'
    ]);

		$user_id = Auth::id();
    $user = User::find($user_id);

    if($request->hasFile('avatar')) {
      if (Input::file('avatar')->isValid()) {
        $path = public_path().'/images/avatar/';
        $old_avatar_path = $path.$user->avatar;

        if (File::exists($old_avatar_path.'.jpg')) {
          File::delete($old_avatar_path.'.jpg');
        }
        if (File::exists($old_avatar_path.'_small.jpg')) {
          File::delete($old_avatar_path.'_small.jpg');
        }

        $fileName = uniqid();

				Image::make(Input::file('avatar'))
          ->fit(120)
          ->save($path.$fileName.'.jpg')
          ->resize(52, 52)
          ->save($path.$fileName.'_small.jpg');

        $user->avatar = $fileName;
      }
      else {
        //Session::flash('info', 'Не удалось загрузить изображение');
        return redirect('profile/edit')
					->with('info', 'Не удалось загрузить изображение');
      }
    }

    $user->name = $request->name;
    $user->save();

		if ($request->has('contact-data')) {
			foreach ($request->{'contact-data'} as $key => $item) {
				if ($item == null) {
					UsersHasContactData::where('user_id', $user_id)
						->where('contact_data_id', $key)
						->delete();
				}
				else {
					UsersHasContactData::updateOrCreate(
						['user_id' => $user_id, 'contact_data_id' => $key],
						['value' => $item]
					);
				}
			}
		}

    return redirect('profile/edit')
			->with('info', 'Изменения сохранены');
  }

	public function getContactData($id = null)
	{
		$data = UsersHasContactData::with([
			'contactData'
		])->where('user_id', $id)
			->orderBy('contact_data_id')
			->get();

		return $data;
	}

  /**
   * Получить и сохранить местоположение пользователя
   */
  public static function getCurrentPosition($request = null)
  {
    // Проверяем установлена ли уже текущая позиция
    $curr_location = Session::has('current_location') ? Session::get('current_location') : null;

    // Проверяем не пустая ли новая позиция
    $new_location = (object) [
      'value' => null,
      'region' => isset($request->region) ? $request->region : null,
      'city' => isset($request->city) ? $request->city : null,
      'setlement' => isset($request->setlement) ? $request->setlement : null,
      'district' => isset($request->district) ? $request->district : null,
      'street' => isset($request->street) ? $request->street : null
    ];

    $empty = true;
    foreach ($new_location as $key => $value) {
      if ($value != null) $empty = false;
    }

    // Если текущая позиция установлена, а новая пустая, то возвращаем текущую
    if ($curr_location != null && $empty && $request != null) {
      $curr_location->value = null;
      return (object) [
        'path' => $request->url().'?'. http_build_query($curr_location)
      ];
    }

    // Если текущая позиция установлена, а новая не пустая, то
    // проверяем отличаются ли новая от текущей
    if ($curr_location != null && !$empty) {
      $differ = false;

      foreach ($curr_location as $key => $value) {
        if ($key != 'value' && $value != $new_location->$key) $differ = true;
      }

      $location_value = null;
      $prev = null;

      foreach ($new_location as $key => $value) {
        if ($value == null || $key == 'value') continue;

        if ($value == $prev || 'г '.$value == $prev) {
          $value = null;
          $new_location->$key = null;
        };

        if ($value != null) {
          if ($location_value != null)
            $location_value = $location_value.', '.$value;
          else
            $location_value = $value;

          $prev = $value;
        }
      }

      // Если текущая позиция отличается от новой, то
      // заменяем текущую на новую, иначе
      // оставляем текущую
      if ($differ) {
        $location = $new_location;
        $location->value = $location_value;
      }
      else {
        $location = $curr_location;
        $location->value = $location_value;
      }

      Session::put('current_location', $location);
      return $location;
    }

    // Если текущая позиция не установлена, а новая пустая, то получаем позицию по IP
    $user_ip = $_SERVER['REMOTE_ADDR'];

    if ($user_ip) {
      // Dadata.ru api
      $headers = [
        "Content-type: application/json",
        "Authorization: Token 541f72d92dd4ccea6f654f5fad08a42b602ca87f"
      ];

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/detectAddressByIp?ip='.$user_ip);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      // END Dadata.ru api

      $output = json_decode($output);

      if (isset($output->location)) {
        $curr_location =  (object) [
          'value' => $output->location,
          'region' => $output->region_with_type,
          'city' => $output->city,
          'setlement' => $output->setlement,
          'district' => $output->district,
          'street' => $output->street_with_type
        ];
      }
    }

    // Если не удалось определить позицию по IP, ставим текущую позицию по умолчанию
    if ($curr_location == null) {
      $curr_location = (object) [
        'value' => 'г Москва',
        'region' => 'г Москва',
        'city' => null,
        'setlement' => null,
        'district' => null,
        'street' => null
      ];
    }

    // Возвращаем тукущую позицию
    Session::put('current_location', $curr_location);
    return $curr_location;
  }
}
