<?php

namespace Piemeram\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Piemeram\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($request->ajax()) {
            return response()->json([
                'auth' => $user->first([
                    'name',
                    'email',
                ]),
                'csrf_token' => csrf_token(),
            ]);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {

        $this->guard()->logout();

        $request->session()->invalidate();

        if ($request->ajax()) {
            $request->session()->regenerateToken();
            return response()->json([
                'csrf_token' => csrf_token(),
            ]);
        }

        return redirect('/');
    }
}
