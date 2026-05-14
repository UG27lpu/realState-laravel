<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'roles' => [
                Role::User->value  => Role::User->label(),
                Role::Agent->value => Role::Agent->label(),
            ],
        ]);
    }

    public function store(RegisterRequest $request, RegisterUserAction $action): RedirectResponse
    {
        $user = $action->execute($request->validated());

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))->with('status', 'Welcome to Estatify, '.$user->name.'!');
    }
}
