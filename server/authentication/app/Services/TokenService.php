<?php

declare(strict_types=1);

namespace Authentication\Services;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenService
{
    public function generateToken(array $extraPayload = []): array
    {
        $token = JWTAuth::fromUser(Auth::user(), $extraPayload);

        return $this->tokenPayload($token, $extraPayload);
    }

    public function refreshToken(): array
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());

        return $this->tokenPayload($token, ['message' => 'Token refreshed']);
    }

    protected function tokenPayload(string $token, array $extraPayload = []): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'user'         => Auth::user(),
            'user_id'      => Auth::user()->id,
            'expires_in'   => JWTAuth::factory()->getTTL() * config('auth.jwt_refresh_minutes'),
        ] + $extraPayload;
    }
}
