<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffForgotPasswordRequest;
use App\Http\Requests\StaffLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the staff login form or process a staff login submission.
     */
    public function login(StaffLoginRequest $request): RedirectResponse|View
    {
        if ($request->isMethod('get')) {
            return view('staff.auth.login', [
                'loginRouteName' => $request->route()?->getName() ?? 'staff.login',
            ]);
        }

        $request->authenticate();
        $request->session()->regenerate();

        $request->session()->put(
            'auth.login_portal',
            $request->routeIs('superadmin.login') ? 'superadmin' : 'staff'
        );

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * End the authenticated staff session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $loginPortal = $request->session()->get('auth.login_portal', 'staff');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($loginPortal === 'superadmin' ? 'superadmin.login' : 'staff.login');
    }

    /**
     * Show or handle the legacy staff forgot password URL.
     */
    public function forgotPassword(StaffForgotPasswordRequest $request): RedirectResponse|View
    {
        if ($request->isMethod('get')) {
            return view('staff.auth.forgot-password');
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->route('staff.forgot_password')->with('status', __($status));
        }

        return redirect()
            ->route('staff.forgot_password')
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
