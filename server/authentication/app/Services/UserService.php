<?php

namespace Authentication\Services;

use Authentication\Events\UserLoggedIn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserService
{
    public function registerUser(array $userData): void
    {
        // logic for user registration

        // Trigger event to save user id and user IP
        event(new UserLoggedIn(Auth::user()->id, $this->generateSessionId(), request()->ip()));
    }

    public function loginUser(array $credentials): array
    {
        $isValid = Auth::attempt($credentials);

        if (!$isValid) {
            // Invalid credentials
            return ['message' => 'Invalid credentials'];
        }

        // Trigger event to save user id and user IP
        event(new UserLoggedIn(Auth::user()->id, $this->generateSessionId(), request()->ip()));

        return ['message' => 'Login successful'];
    }

    protected function generateSessionId(): string
    {
        return Str::uuid()->toString();
    }
}
