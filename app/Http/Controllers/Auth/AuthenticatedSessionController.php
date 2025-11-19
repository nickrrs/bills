<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        $loginState = $request->query('loginState', false);
        $loginState = filter_var($loginState, FILTER_VALIDATE_BOOLEAN);

        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
            'loginState' => $loginState,
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // Remove todas as outras sessões do mesmo usuário (logout de outros navegadores)
        // IMPORTANTE: fazer isso ANTES de regenerar a sessão
        if ($user) {
            $this->logoutOtherSessions($user);
        }

        // Regenera a sessão para criar uma nova sessão segura
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Remove todas as sessões do usuário.
     * Como vamos regenerar a sessão logo após, não precisamos preservar a atual.
     */
    protected function logoutOtherSessions($user): void
    {
        $sessionTable = config('session.table', 'sessions');

        // Deleta todas as sessões do usuário
        // A nova sessão será criada após regenerate()
        DB::table($sessionTable)
            ->where('user_id', $user->id)
            ->delete();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $sessionId = $request->session()->getId();

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Remove a sessão do banco de dados
        if ($user && $sessionId) {
            $sessionTable = config('session.table', 'sessions');
            DB::table($sessionTable)
                ->where('id', $sessionId)
                ->delete();
        }

        return redirect('/');
    }
}
