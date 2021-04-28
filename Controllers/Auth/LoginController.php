<?php

namespace App\Http\Controllers\Auth;

use App\Enums\GuardType;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginPostValidation;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;

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

    use ThrottlesLogins;

    /**
     * The maximum number of attempts to allow.
     *
     * @return int
     */
    protected int $maxAttempts = 10;

    /**
     * The number of minutes to throttle for.
     *
     * @return int
     */
    protected int $decayMinutes = 5;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:'.GuardType::BUSINESS)->except('logout');
        $this->middleware('guest:'.GuardType::STAFF)->except('logout');
    }

    /**
     * Username used in ThrottlesLogins trait
     *
     * @return string
     */
    public function username() : string
    {
        return "email";
    }

    /**
     * Method to show admin login view
     */
    public function showAdminLoginForm()
    {
        return view('auth.admin.login');
    }

    /**
     * Method for admin login process
     * @param LoginPostValidation $request
     */
    public function processAdminLoginForm(LoginPostValidation $request)
    {
        // First check if the user has too many login attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = [
            'email'      => $request->email,
            'password'   => $request->password,
            'status'     => 'active',
            'deleted_at' => null
        ];

        if (!Auth::attempt($credentials)) {
            $this->incrementLoginAttempts($request);
            return redirect(route('admin.login.form'))->withErrors(trans('auth.failed'))->withInput($request->only('email'));
        }

        $language = Auth::user()->language;
        app()->setLocale($language);
        session()->put('language', $language);

        return redirect()->intended(route('admin.home'));
    }

    /**
     * Method to show business login view
     */
    public function showBusinessLoginForm()
    {
        return view('auth.business.login');
    }

    /**
     * Method for business login process
     * @param LoginPostValidation $request
     */
    public function processBusinessLoginForm(LoginPostValidation $request)
    {
        // First check if the user has too many login attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = [
            'email'      => $request->email,
            'password'   => $request->password,
            'status'     => 'active',
            'deleted_at' => null
        ];

        if (!Auth::guard(GuardType::BUSINESS)->attempt($credentials)) {
            $this->incrementLoginAttempts($request);
            return redirect(route('business.login.form'))->withErrors(trans('auth.failed'))->withInput($request->only('email'));
        }

        $language = Auth::guard(GuardType::BUSINESS)->user()->language;
        app()->setLocale($language);
        session()->put('language', $language);

        return redirect()->intended(route('business.home'));
    }

    /**
     * Method to show staff login view
     */
    public function showStaffLoginForm()
    {
        return view('auth.staff.login');
    }

    /**
     * Method for Staff login process
     * @param LoginPostValidation $request
     */
    public function processStaffLoginForm(LoginPostValidation $request)
    {
        // First check if the user has too many login attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = [
            'email'      => $request->email,
            'password'   => $request->password,
            'status'     => 'active',
            'deleted_at' => null
        ];

        if (!Auth::guard(GuardType::STAFF)->attempt($credentials)) {
            $this->incrementLoginAttempts($request);
            return redirect(route('staff.login.form'))->withErrors(trans('auth.failed'))->withInput($request->only('email'));
        }

        return redirect()->intended(route('staff.home'));
    }

    /**
     * Logout Process
     */
    public function logout()
    {
        $route = "admin.login.form";
        if (Auth::guard(GuardType::BUSINESS)->check()) $route = "business.login.form";
        if (Auth::guard(GuardType::STAFF)->check()) $route = "staff.login.form";
        Auth::logout();
        return redirect()->route($route);
    }
}
