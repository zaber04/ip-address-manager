<?php

declare(strict_types=1);

namespace Authentication\Http\Controllers;

use Authentication\Models\User;
use Gateway\Traits\ApiResponse;
use Gateway\Traits\LoggingTrait;
use Gateway\Traits\ExceptionHandlerTrait;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    use ApiResponse;
    use LoggingTrait;
    use ExceptionHandlerTrait;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'login', 'refresh', 'logout']]);
    }

    /**
     * Register a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            // Validation
            $this->validate($request, User::$rules);

            // Create user
            $user = User::create($request->all());

            // Generate token
            $token = JWTAuth::fromUser($user);
            print_r($token);

            // Refresh token for later use
            // $refreshToken = JWTAuth::refresh(JWTAuth::getToken());

            // Prepare token payload
            $tokenArray = $this->tokenPayload($token, ['user' => $user, 'message' => 'User registered successfully']);

            return $this->jsonResponseWith($tokenArray, JsonResponse::HTTP_CREATED);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuthController@register'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuthController@register'];
            return $this->handleException($request, $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request): JsonResponse
    {
        try {
            // Validation
            $this->validate($request, [
                'email'    => 'required|email',
                'password' => 'required|string'
            ]);

            // Attempt to log in
            $credentials = $request->only(['email', 'password']);
            $token = JWTAuth::attempt($credentials);

            if (!$token) {
                // Invalid credentials
                return $this->jsonResponseWith(['message' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            // Refresh token for later use
            // $refreshToken = JWTAuth::refresh(JWTAuth::getToken());

            // Prepare token payload
            $tokenArray = $this->tokenPayload($token, ['message' => 'Login successful']);

            return $this->jsonResponseWith($tokenArray, JsonResponse::HTTP_OK);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            // Handle exceptions
            $errorInfo = ['url' => $request->path(), 'function' => 'AuthController@login'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            // Handle general exception
            $errorInfo = ['url' => $request->path(), 'function' => 'AuthController@login'];
            return $this->handleException($request, $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Refresh JWT token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        try {
            // Refresh token
            $tokenArray = $this->tokenPayload(JWTAuth::refresh(JWTAuth::getToken()), ['message' => 'Token refreshed']);

            return $this->jsonResponseWith($tokenArray);
        } catch (ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => request()->path(), 'function' => 'AuthController@refresh'];
            return $this->handleException(request(), $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['url' => request()->path(), 'function' => 'AuthController@refresh'];
            return $this->handleException(request(), $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Log the user out (invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            Auth::logout();

            return $this->jsonResponseWith(['message' => 'Successfully logged out']);
        } catch (ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => request()->path(), 'function' => 'AuthController@logout'];
            return $this->handleException(request(), $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['url' => request()->path(), 'function' => 'AuthController@logout'];
            return $this->handleException(request(), $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get the authenticated user.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            // Get authenticated user
            return $this->jsonResponseWith(Auth::user());
        } catch (ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => request()->path(), 'function' => 'AuthController@me'];
            return $this->handleException(request(), $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['url' => request()->path(), 'function' => 'AuthController@me'];
            return $this->handleException(request(), $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Respond with a JWT token.
     *
     * @param string $token
     * @return JsonResponse
     */
    protected function tokenPayload(string $token,  array $extraPayload = []): array
    {
        $tokenArray = [
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'user'          => auth()->user(),
            'expires_in'    => JWTAuth::factory()->getTTL() * env('JWT_REFRESH_MINUTES', 30)
        ];

        return array_merge($tokenArray, $extraPayload);
    }
}
