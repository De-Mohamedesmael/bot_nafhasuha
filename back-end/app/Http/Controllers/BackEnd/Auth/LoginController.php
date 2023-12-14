<?php

namespace App\Http\Controllers\BackEnd\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Utils\NotificationUtil;
use Carbon\Carbon;
use Doctrine\DBAL\Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating admins for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect admins after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';
    protected $guard = 'admin';
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(NotificationUtil $notificationUtil)
    {
        $this->middleware('guest')->except('logout');
    }
    protected function authenticated(Request $request, $user)
    {
        return redirect( $this->redirectTo);
    }
    protected function guard()
    {
        return Auth::guard($this->guard);
    }
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        // Get the user details from database and check if user is exist and active.
        $user = Admin::where('email', $request->email)->first();
        if ($user && !$user->is_active) {
            throw ValidationException::withMessages([$this->username() => __('User has been desactivated.')]);
        }

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);


    }
    public function showLoginForm()
    {

        return view('back-end.auth.login');
    }

    /**
     * Log the admin out of the application.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {

        $this->guard()->logout();

        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect()->route('admin.home');
    }
}
