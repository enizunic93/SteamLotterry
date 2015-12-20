<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Invisnik\LaravelSteamAuth\SteamAuth;
use Symfony\Component\HttpFoundation\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @param SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->steam = $steam;

        $this->redirectAfterLogout = '/';
    }

    public function login()
    {
        $response = $this->steam->validate();

        Log::alert(json_encode($this->steam));
        Log::alert(json_encode(\Config::get('steam-auth.api_key')));

        if ($response) {
            $info = $this->steam->getUserInfo();
            if (!is_null($info)) {
                $user = User::where('steam_id', $info->getSteamID64())->first();
                if (!is_null($user)) {
                    Auth::login($user, true);
                    return redirect('/'); // redirect to site
                } else {
                    $data = [
                        'name' => $info->getNick(),
                        'steam_id' => $info->getSteamID64(),
                        'avatar' => $info->getProfilePictureFull(),
                    ];

                    $user = User::create($data);
                    Auth::login($user, true);

                    return redirect('/'); // redirect to site
                }
            }
        } else {
            return $this->steam->redirect(); // redirect to Steam login page
        }
    }
}
