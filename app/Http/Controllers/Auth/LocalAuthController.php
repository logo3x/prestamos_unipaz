<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocalAuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials, remember: true)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Credenciales incorrectas.');
        }

        $request->session()->regenerate();

        return redirect()->route('disponibilidad');
    }
}
