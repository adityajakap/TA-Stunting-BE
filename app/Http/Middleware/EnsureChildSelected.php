<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureChildSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Only enforce for orangtua
        if ($user && $user->role === 'orangtua') {
            // Check if user has any children
            if ($user->children()->count() === 0) {
                // Not enforcing redirect to a "create child" page forcefully here to avoid redirect loops 
                // if they are already on the dashboard or create child route,
                // but if they try to access a specific feature without a child, we redirect.
                return redirect()->route('orangtua.dashboard')->with('error', 'Silakan tambah data anak terlebih dahulu.');
            }

            // Check if a child is selected
            if (!session()->has('selected_child_id')) {
                return redirect()->route('orangtua.dashboard')->with('error', 'Silakan pilih anak terlebih dahulu untuk mengakses fitur ini.');
            }
            
            // Validate that the selected child belongs to this user
            $selectedChildId = session('selected_child_id');
            $childBelongsToUser = $user->children()->where('id', $selectedChildId)->exists();
            
            if (!$childBelongsToUser) {
                session()->forget('selected_child_id');
                return redirect()->route('orangtua.dashboard')->with('error', 'Anak yang dipilih tidak valid.');
            }
        }

        return $next($request);
    }
}
