<?php

namespace Vesaka\Games\Http\Requests\Auth;

use Vesaka\Core\Http\Requests\ApiRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Description of RegisterRequest
 *
 * @author Vesaka
 */
class RegisterRequest extends ApiRequest {
    
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

}
