<?php

namespace Vesaka\Games\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth as AuthGuard;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Vesaka\Core\Http\Requests\ApiRequest;

/**
 * Description of LoginRequest
 *
 * @author Vesaka
 */
class LoginRequest extends ApiRequest {
    public function rules() {
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        if (! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $user = User::select('email')->firstOrNew([
                'name' => $this->email,
            ]);
            $this->merge(['email' => $user->email ?? 'a3243']);
        } else {
            $rules['email'] = 'exists:users,email';
        }

        return [
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'email.required' => 'Email is required',
            'name.exists' => 'User not found',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate() {
        $this->ensureIsNotRateLimited();

        if (! AuthGuard::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'not_found',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited() {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey() {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
