<?php

namespace Vesaka\Games\Traits;

use Illuminate\Support\Str;

trait CanBeGuest
{
    public function getIsGuestAttribute(): bool {
        return Str::endsWith($this->email, GUEST_EMAIL_DOMAIN);
    }

    public static function isGuestUser(): bool {
        return Str::endsWith(auth()->user()->email, GUEST_EMAIL_DOMAIN);
    }
    
}