<?php

namespace Vesaka\Games\Http\Requests\Auth;

use Illuminate\Validation\Rules\Password;
use Vesaka\Core\Http\Requests\ApiRequest;
use Vesaka\Games\Rules\RateLimitRule;

class CreateGuestRequest extends ApiRequest {

    protected string $messagesParser = 'default';
    public function rules() {
        return [
            'guest' => [new RateLimitRule('createGuest', 5, 10)],
        ];
    }
    
}