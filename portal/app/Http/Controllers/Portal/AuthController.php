<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('portal.auth.login');
    }

    public function login(Request $req)
    {
        $req->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $response = Http::timeout(config('services.portal.timeout'))
            ->post(config('services.portal.base_uri').'/login', [
                'email'    => $req->email,
                'password' => $req->password,
            ]);

        if ($response->status() !== 200) {
            return back()
                ->withErrors(['email' => 'Credenciales inválidas'])
                ->withInput();
        }
        $data = $response->json();
        session([
            'portal_token' => $data['token'],
            'portal_user'  => $data['user'],
        ]);

        return redirect()->route('portal.dashboard');
    }

    public function logout()
    {
        session()->forget(['portal_token','portal_user']);
        return redirect()->route('login');
    }
}
