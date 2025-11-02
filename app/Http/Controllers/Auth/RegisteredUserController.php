<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'string', 'in:tenant,landlord'],
            'phone' => ['required', 'string', 'max:20'],
            'document_type' => ['nullable', 'string', 'in:philippine_id,drivers_license,sss_gsis,passport,birth_certificate,other'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'name' => $request->first_name . ' ' . $request->surname,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'document_type' => $request->document_type,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Do not auto-login user after registration, redirect to login page instead
        // Auth::login($user);

        // Instead of redirecting to a separate success page, redirect to home with success flag and user data in session
        return redirect('/')->with('registration_success', true)->with('registered_user', $user);
    }
}
