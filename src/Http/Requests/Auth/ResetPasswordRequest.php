<?php

namespace Vesaka\Games\Http\Requests\Auth;

use Vesaka\Core\Http\Requests\ApiRequest;
use Illuminate\Validation\Rules\Password;
class ResetPasswordRequest extends ApiRequest {
    protected string $messagesParser = 'simple';

    public function rules() {
        return [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => ['required', 'confirmed', 'max:64', Password::defaults()],
        ];
        
    }
}