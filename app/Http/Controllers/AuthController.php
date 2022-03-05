<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth.show');
    }

    public function store(Request $request)
    {
       $data = $request->validate([
            'email' => 'required|email|string'
        ]);

        $user = User::query()->firstOrCreate([
            'name' => explode('@', $data['email'])[0],
            'email' => $data['email'],
        ]);

        $user->fresh()->sendLinkLogin();

        session()->flash('success', true);

        return redirect()->back();
    }

    public function verifyToken(Request $request)
    {
        $token = \App\Models\LoginToken::whereToken(hash('sha256', base64_decode($request->token)))->firstOrFail();

        abort_unless($request->hasValidSignature() && $token->isValid(), 401);

        $token->user->update([
            'email_verified_at' => now()
        ]);

        $token->consume();

        Auth::login($token->user);

        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
}
