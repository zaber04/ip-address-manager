<?php

declare(strict_types=1);

namespace Authentication\Traits;

use Authentication\Models\User;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;


trait TokenTrait
{
    /**
     * Generates a JWT token
     */
    private function generateToken(User $user, string $session_id): string {
        // Include additional claims during JWT creation
        $userProperties = [
            // send selected properties to keep jwt shorter
            "first_name" => $user->first_name,
            "last_name"  => $user->last_name,
            "email"      => $user->email,
            "user_id"    => $user->id
        ];
        $payload = JWTFactory::sub($user->id)
            ->user($userProperties)
            ->session_id($session_id)
            ->make();

        $tokenObject = JWTAuth::encode($payload);
        $token = $tokenObject->get();

        return $token;
    }

    /**
     * Decodes JWT token from header and returns the payload as array
     */
    private function getTokenArrayFromHeader(Request $request): array {
        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            // For now, let's return an empty array if header is missing
            // we may throw an exception in the controller if needed
            // we don't really need to log this since controller might reject the request anyway
            return [];
        }

        // Extract the token from the Authorization header
        list($tokenType, $token) = explode(' ', $authHeader, 2);

        // Decode and validate the token
        $decodedToken = JWTAuth::decode($token);

        try {
            // Decode and validate the token
            $decodedToken = JWTAuth::decode($token);
        } catch (JWTException $e) {
            // For now, let's return an empty array if header is missing
            // we may throw an exception in the controller if needed
            // we don't really need to log this since controller might reject the request anyway
            return [];
        }

        return $decodedToken;
    }

    /**
     * Assigns the jwt token as authorization header
     */
    private function setTokenInHeader(string $token, PendingRequest $http): PendingRequest {
        $headers = ['Authorization' => 'Bearer ' . $token];

        // Set headers on the HTTP client
        $http = $http->withHeaders($headers);

        return $http;
    }

    /**
     * Generates a UUID which we use to mark a users session
     */
    private function generateSessionId(): string
    {
        return Str::uuid()->toString();
    }

    /**
     * Respond with a JWT token.
     *
     * @param string $token
     * @return JsonResponse
     */
    private function tokenPayload(string $token,  array $extraPayload = []): array
    {
        $tokenArray = [
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'user'          => auth()->user(),
            'expires_in'    => JWTAuth::factory()->getTTL() *  config('auth.jwt_refresh_minutes')
        ];

        return array_merge($tokenArray, $extraPayload);
    }

    /**
     * Refresh the token
     */
    private function getRefreshToken() {
        return JWTAuth::refresh(JWTAuth::getToken());
    }
}
