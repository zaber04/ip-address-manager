<?php

declare(strict_types=1);

namespace Authentication\Http\Controllers;

use Authentication\Models\User;
use Authentication\Events\UserEvent;
use Authentication\Traits\TokenTrait;
// use Authentication\Exceptions\AuthenticationException;
// use Authentication\Exceptions\InvalidCredentialsException;
// use Authentication\Exceptions\UserRegistrationException;
// use Authentication\Exceptions\TokenRefreshException;
// use Authentication\Exceptions\UserLogoutException;
// use Authentication\Services\TokenService;
// use Authentication\Services\UserService;
use Gateway\Enums\ActionEnum;
use Gateway\Traits\ApiResponseTrait;
use Gateway\Traits\ExceptionHandlerTrait;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


// @TODO: Implement comprehensive exception handling for auth microservice @zaber04
class AuthController extends Controller
{
    use ApiResponseTrait;
    use ExceptionHandlerTrait;
    use TokenTrait;

    // protected $tokenService;
    // protected $userService;

    // @TODO: Properly integrate and test the two services by dependency injection for better SOC
    // both services now requires a lot of rework and basic features are now being provided by Traits
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
        // @TODO: Use the token-service & user-service for better SOC
        try {
            // throw exception if validation fails
            $this->validate($request, User::$rules);

            // Create user
            $user = User::create($request->all());

            // Generate token
            $session_id = $this->generateSessionId();
            $token      = $this->generateToken($user, $session_id);

            // Trigger event to save user id and user IP
            // we didn't use schema object as parameter due to time constraints of the project
            event(new UserEvent($user->id, $session_id, ActionEnum::LOGIN, $request->ip()));

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
            // Validate using model rules
            User::validate([
                'email'    => $request->input('email'),
                'password' => $request->input('password')
            ]);

            // Attempt to log in
            $credentials = $request->only(['email', 'password']);
            $isValid     = auth()->attempt($credentials);

            if (!$isValid) {
                // Invalid credentials
                return $this->jsonResponseWith(['message' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            $user       = User::where('email', $credentials['email'])->first();
            $session_id = $this->generateSessionId();
            $token      = $this->generateToken($user, $session_id);

            // Trigger event to save user id and user IP
            event(new UserEvent($user->id, $session_id, ActionEnum::LOGIN, $request->ip()));

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
            $token      = $this->getRefreshToken();
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
    public function logout(Request $request): JsonResponse
    {
        try {
            $tokenArray = $this->getTokenArrayFromHeader($request);

            // Access user_id & session_id from the token payload
            $userId    = $tokenArray['user_id'] ?? $tokenArray['sub'] ?? '';
            $sessionId = $tokenArray['session_id'] ?? '';

            // send logout event to audit-trail-report
            event(new UserEvent($userId, $sessionId, ActionEnum::LOGOUT, $request->ip()));

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
}
