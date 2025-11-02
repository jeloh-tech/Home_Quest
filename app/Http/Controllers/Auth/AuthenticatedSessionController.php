<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Migrate guest likes to authenticated user before session regeneration
        $user = $request->user();
        $guestLikes = $request->session()->get('guest_likes', []);
        if (!empty($guestLikes)) {
            Log::info('Migrating guest likes during login', [
                'user_id' => $user->id,
                'guest_likes' => $guestLikes
            ]);

            // Migrate guest likes to authenticated user
            foreach ($guestLikes as $guestListingId) {
                if (!$user->likedListings()->where('listing_id', $guestListingId)->exists()) {
                    $user->likedListings()->attach($guestListingId);
                    Log::info('Attached guest like to user', [
                        'user_id' => $user->id,
                        'listing_id' => $guestListingId
                    ]);
                } else {
                    Log::info('Guest like already exists for user', [
                        'user_id' => $user->id,
                        'listing_id' => $guestListingId
                    ]);
                }
            }
            // Clear guest likes from session
            $request->session()->forget('guest_likes');
            Log::info('Cleared guest likes from session for user', ['user_id' => $user->id]);
        } else {
            Log::info('No guest likes to migrate for user', ['user_id' => $user->id]);
        }

        $request->session()->regenerate();

        if ($user->role === 'tenant') {
            return redirect()->intended(route('tenant.dashboard', [], false))->with('status', 'Your account successfully logged in!');
        } elseif ($user->role === 'landlord') {
            return redirect()->intended(route('landlord.dashboard', [], false))->with('status', 'Your account successfully logged in!');
        } elseif ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard', [], false))->with('status', 'Your account successfully logged in!');
        }

        return redirect()->intended(route('dashboard', [], false))->with('status', 'Your account successfully logged in!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
