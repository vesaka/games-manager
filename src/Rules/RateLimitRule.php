<?php

namespace Vesaka\Games\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class RateLimitRule implements ValidationRule {

    protected string $key;

    protected int $maxAttempts;

    protected int $decayRate;

    public function __construct(string $key = 'rate-limitter', int $maxAttempts = 5, int $decayRate = 10) { 
        $this->key = $key . ':' . request()->ip();
        $this->maxAttempts = $maxAttempts;
        $this->decayRate = $decayRate;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (auth()->check()) {
            return;
        }
        
        $executed = RateLimiter::attempt($this->key, $this->maxAttempts, function() {
            return true;
        }, $this->decayRate);

        if(!$executed) {
            $fail('too_many_attempts:' . RateLimiter::availableIn($this->key));
        }
    }

}
