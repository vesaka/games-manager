<?php

namespace Vesaka\Games\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Vesaka\Games\Http\Requests\Auth\LoginRequest;
use Vesaka\Games\Http\Requests\Auth\RegisterRequest;
use Vesaka\Games\Models\Player;
use Illuminate\Validation\ValidationException;

/**
 * Description of AuthController
 *
 * @author vesak
 */
class AuthController extends Controller {
    public function login(LoginRequest $request) {
        $request->authenticate();
        try {
            $user = $request->user()->only('id', 'name');
            $user['token'] = $request->user()->createToken($request->header('User-Agent'))->plainTextToken;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
        
        return $user;
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Vesaka\Games\Http\Requests\Auth\RegisterRequest  $request
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(RegisterRequest $request) {
        $user = Player::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        event(new Registered($user));

        return $user->only('id', 'name');
    }

    public function sendPasswordResetLink(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return ['message' => 'Sent'];
        }

        throw ValidationException::withMessages([
            'email' => $status,
        ]);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => ['required', 'confirmed', 'max:64', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return ['status' => $status];
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
