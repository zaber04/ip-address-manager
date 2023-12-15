<?php

namespace Authentication\Services;

use Authentication\Events\UserEvent;
use Gateway\Enums\ActionEnum;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserService
{
    public function registerUser(array $userData): void
    {
        // logic for user registration

        // Trigger event to save user id and user IP
        event(new UserEvent(Auth::user()->id, $this->generateSessionId(), ActionEnum::LOGIN, request()->ip()));
    }

    public function loginUser(array $credentials): array
    {
        $isValid = Auth::attempt($credentials);

        if (!$isValid) {
            // Invalid credentials
            return ['message' => 'Invalid credentials'];
        }

        // Trigger event to save user id and user IP
        event(new UserEvent(Auth::user()->id, $this->generateSessionId(), ActionEnum::LOGIN, request()->ip()));

        return ['message' => 'Login successful'];
    }

    protected function generateSessionId(): string
    {
        return Str::uuid()->toString();
    }
}
