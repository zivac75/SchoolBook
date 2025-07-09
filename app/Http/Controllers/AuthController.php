<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CodeInscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\Attributes\Middleware;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'api') {
                return redirect('/dashboard/api');
            } elseif ($user->role === 'etudiant') {
                return redirect('/dashboard/etudiant');
            } else {
                return redirect('/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'code_inscription' => ['required', 'string', 'exists:codes_inscription,code'],
        ]);

        $code = CodeInscription::where('code', $request->code_inscription)
            ->where('utilise', false)
            ->first();

        if (!$code) {
            return back()->withErrors([
                'code_inscription' => 'Ce code d\'inscription est invalide ou a déjà été utilisé.',
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $code->role,
            'code_utilise_id' => $code->id,
        ]);

        $code->update(['utilise' => true]);

        Auth::login($user);

        if ($user->role === 'api') {
            return redirect('/dashboard/api');
        } elseif ($user->role === 'etudiant') {
            return redirect('/dashboard/etudiant');
        } else {
            return redirect('/dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
