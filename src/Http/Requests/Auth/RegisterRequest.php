<?php

namespace Vesaka\Games\Http\Requests\Auth;

use Illuminate\Validation\Rules\Password;
use Vesaka\Core\Http\Requests\ApiRequest;

/**
 * Description of RegisterRequest
 *
 * @author Vesaka
 */
class RegisterRequest extends ApiRequest {
    public function rules() {
        return [
            'name' => 'required|string|alphanum|max:32|unique:users,name',
            'email' => 'required|string|email|max:64|unique:users',
            'password' => ['required', 'confirmed', 'string', 'max:64', Password::defaults()],
            'accept' => 'accepted'
        ];
    }
    
}
