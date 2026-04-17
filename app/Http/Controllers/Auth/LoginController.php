<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // ✅ Email ki jagah id_card use hoga
    public function username()
    {
        return 'id_card';
    }

    // ✅ Validation
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'id_card' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    // ✅ SUCCESS LOGIN LOG
    protected function authenticated(Request $request, $user)
    {
        $request->session()->put('session_version', (int) ($user->session_version ?? 1));

        $this->audit(
            'Login',
            'User Logged In',
            null,
            null,
            [
                'user_id' => $user->id,
                'id_card' => $user->id_card,
                'name' => $user->name ?? null
            ]
        );

        if ($request->filled('redirect_to') && $request->input('redirect_to') === route('website.payment')) {
            return redirect()->to(route('website.payment'));
        }
    }

    // ✅ FAILED LOGIN LOG
    protected function sendFailedLoginResponse(Request $request)
    {
        $this->audit(
            'Login Failed',
            'Wrong Credentials',
            null,
            null,
            [
                'id_card_attempt' => $request->id_card
            ]
        );

        throw ValidationException::withMessages([
            $this->username() => [__('web.login_failed')],
        ]);
    }

    // ✅ LOGOUT LOG
    public function logout(Request $request)
    {
        $user = Auth::user();

        $this->audit(
            'Logout',
            'User Logged Out',
            null,
            [
                'user_id' => $user->id ?? null,
                'name' => $user->name ?? null
            ],
            null
        );

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // ✅ YOUR AUDIT FUNCTION
    private function audit(string $event, string $action, ?int $auditableId, $old = null, $new = null): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'module' => 'Auth',
            'action' => $action,
            'auditable_type' => null,
            'auditable_id' => $auditableId,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
    }
}

