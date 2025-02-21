<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{

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

    public function store(Request $request)
    {
        // Attempt to login
        if (! auth()->attempt($request->only('email', 'password'), $request->get('remember', false))) {
            return response()->json([
                'status' => null,
                'success' => false,
                'error' => true,
                'message' => "Failed",
                'data' => null,
                'redirect' => null,
            ]);
        }

        return response()->json([
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => "success",
            'data' => null,
            'redirect' => null
        ]);
    }

    public function create()
    {
        return view('auth.login');
    }
}
