<?php

declare(strict_types=1);

namespace Authentication\Http\Controllers;

use Authentication\Models\User;
use Authentication\Events\UserLoggedIn;
// use Authentication\Exceptions\AuthenticationException;
// use Authentication\Exceptions\InvalidCredentialsException;
// use Authentication\Exceptions\UserRegistrationException;
// use Authentication\Exceptions\TokenRefreshException;
// use Authentication\Exceptions\UserLogoutException;
use Authentication\Services\TokenService;
use Authentication\Services\UserService;
use Gateway\Traits\ApiResponse;
// use Gateway\Traits\LoggingTrait;
use Gateway\Traits\ExceptionHandlerTrait;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;


// @TODO: Implement comprehensive exception handling for auth microservice @zaber04
class AuthController extends Controller
{
    use ApiResponse;
    // use LoggingTrait;
    use ExceptionHandlerTrait;

    // protected $tokenService;
    // protected $userService;

    // @TODO: Properly integrate and test the two services by dependency injection for better SOC
    public function __construct(/*TokenService $tokenService, UserService $userService*/)
    {
        $this->middleware('auth:api', ['except' => ['register', 'login', 'refresh', 'logout']]);
        // $this->tokenService = $tokenService;
        // $this->userService  = $userService;
    }

    /**
     * Register a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        // @TODO: Use the tokenservice & userservice for better SOC
        try {
            // Validation
            $this->validate($request, User::$rules);

            // Create user
            $user = User::create($request->all());

            // Include additional claims during JWT creation
            $claims = [
                'session_id' => $this->generateSessionId(),
                'user_id'    => $user->id,
                'user'       => $user
            ];

            // Generate token
            $token = JWTAuth::fromUser($user, $claims);

            // Trigger event to save user id and user IP
            event(new UserLoggedIn($claims['user_id'], $claims['session_id'], $request->ip()));

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
        // @TODO: Use the tokenservice & userservice for better SOC
        try {
            // Validation
            $this->validate($request, [
                'email'    => 'required|email|max:255',
                'password' => 'required|string|min:8|max:255'
            ]);

            // Attempt to log in
            $credentials = $request->only(['email', 'password']);
            $isValid     = auth()->attempt($credentials);

            if (!$isValid) {
                // Invalid credentials
                return $this->jsonResponseWith(['message' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            $user  = User::where('email', $credentials['email'])->first();
            $session_id = $this->generateSessionId();

            // Include additional claims during JWT creation
            $userProperties = [
                // send selected properties to keep jwt shorter
                "first_name" => $user->first_name,
                "last_name"  => $user->last_name,
                "email"      => $user->email
            ];
            $payload = JWTFactory::sub($user->id)
                ->user($userProperties)
                ->session_id($session_id)
                ->make();

            $tokenObject = JWTAuth::encode($payload);
            $token = $tokenObject->get();

            // Trigger event to save user id and user IP
            event(new UserLoggedIn($user->id, $session_id, $request->ip()));

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
            $token = JWTAuth::refresh(JWTAuth::getToken());
            $tokenArray = $this->tokenPayload($token, ['message' => 'Token refreshed']);

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
    protected function tokenPayload($token,  array $extraPayload = []): array
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
     * Generates a UUID which we use to mark a users session
     */
    protected function generateSessionId(): string
    {
        return Str::uuid()->toString();
    }
}
