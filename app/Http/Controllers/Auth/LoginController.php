<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FavoritesController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Socialite;
use Image;
use App\User;
use App\UsersHasContactData;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
		public function redirectTo()
		{
      FavoritesController::setFavorites();

			if (session('url.intended'))
				return session('url.intended');
			else
				return url('/');
		}

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('guest')->except('logout');
    }

		public function showLoginForm()
		{
			if (url()->previous() != url('login')) {
				session(['url.intended' => url()->previous()]);
			}

			return view('auth.login');
		}

		public function redirectToProvider()
		{
			return Socialite::with('vkontakte')->redirect();
		}

		public function handleProviderCallback()
		{
			try {
				$user = Socialite::driver('vkontakte')->user();
			}
			catch (Exception $e) {
				return abort(404);
			}

			$authUserId = $this->findOrCreateUser($user);

			//Auth::login(, true);
      Auth::loginUsingId($authUserId, true);

      return redirect($this->redirectTo());
		}

		private function findOrCreateUser($vkUser)
		{
			$authUser = User::where('vk_id', $vkUser->user['id'])->first();

			if ($authUser) return $authUser->id;

      $id = abs(crc32(uniqid()));
      $fileName = null;

      if ($vkUser->user['photo'] != null) {
        $path = public_path().'/images/avatar/';
        $fileName = uniqid();

				Image::make($vkUser->user['photo'])
          ->fit(120, 120, function ($constraint) {
            $constraint->upsize();
          })
          ->save($path.$fileName.'.jpg')
          ->resize(52, 52)
          ->save($path.$fileName.'_small.jpg');
      }

      User::create([
        'id' => $id,
				'vk_id' => $vkUser->user['id'],
				'name' => $vkUser->user['first_name'],
				'email' => $vkUser->user['email'],
        'avatar' => $fileName
			]);

      UsersHasContactData::create([
        'contact_data_id' => 2,
        'user_id' => $id,
        'value' => $vkUser->user['screen_name'],
      ]);

			return $id;
		}

		/*public function login(Request $request)
    {
      $auth = false;
      $credentials = $request->only('email', 'password');

      $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required|min:6'
			]);

      if (Auth::attempt($credentials))
				$auth = true;

      if (!$auth)
        return redirect('login');

      Auth::loginUsingId($request->id);

      $this->redirectTo();
      // if ($request->ajax() && $auth) {
      //   return response()->json([
      //     'auth' => 'success'
      //   ]);
      // }
      //
      // return response()->json([
      //   'auth' => 'false'
      // ], 401);
    }*/
}
