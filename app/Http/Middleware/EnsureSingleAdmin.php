<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureSingleAdmin
{
    /**
     * Designated authorized single administrator email.
     * Only this designated administrator is authorized to access customer complaints and sensitive bank data.
     */
    protected array $authorizedAdmins = [
        'admin@bankmajalengka.co.id',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Check if user is the authorized single administrator or has superadmin/admin role
        $isAuthorized = in_array(strtolower($user->email), $this->authorizedAdmins, true) 
            || $user->role === 'superadmin' 
            || ($user->role === 'admin' && $user->id === 1);

        if (!$isAuthorized) {
            Log::warning("Unauthorized admin access attempt by user #{$user->id} ({$user->email}) from IP {$request->ip()} to {$request->path()}");
            abort(403, 'Akses Dibatasi: Modul panel admin ini dikhususkan hanya untuk Administrator Utama Perumda BPR Majalengka.');
        }

        return $next($request);
    }
}
