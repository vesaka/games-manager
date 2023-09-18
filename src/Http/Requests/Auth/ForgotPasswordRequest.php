<?php

namespace Vesaka\Games\Http\Requests\Auth;

use Vesaka\Core\Http\Requests\ApiRequest;
use Illuminate\Validation\Rules\Password;
class ForgotPasswordRequest extends ApiRequest {
    protected string $messagesParser = 'simple';

    public function rules() {
        return [
            'email' => 'required|email|exists:users,email',
        ];
        
    }
}