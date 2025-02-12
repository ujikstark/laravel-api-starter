<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function create()
    {
        return view('auth.login');
    }


    public function store(Request $request)
    {
        // Attempt to login
        if (! auth()->attempt($request->only('email', 'password'), $request->get('remember', false))) {
            return response()->json([
                'status' => null,
                'success' => false,
                'error' => true,
                'message' => "Failed", // must use global language
                'data' => null,
                'redirect' => null,
            ]);
        }

        // Redirect to landing page if is user
        // $url = route($user->landing_page, ['company_id' => $company->id]);


        return response()->json([
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => "Redirecting", // must use global language
            'data' => null,
            'redirect' => null
        ]);
    }
}
