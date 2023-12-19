<?php

namespace Tests;

use Authentication\Http\Controllers\AuthController;
use Tests\TestCase;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Authentication\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthControllerTest.
 *
 * run: ./vendor/bin/phpunit tests/AuthControllerTest.php
 *
 * @covers \Authentication\Http\Controllers\AuthController
 */
final class AuthControllerTest extends TestCase
{
    private AuthController $authController;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->authController = new AuthController();
        $this->app->instance(AuthController::class, $this->authController);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->authController);
    }

    /**
     * testRegister: Authentication\Http\Controllers\AuthController::register()
     */
    public function testRegister(): void
    {
        // set required data in request to register a user
        $request = new Request();
        $request->replace(['first_name' => 'Test', 'last_name' => 'User', 'email' => 'test@example.com', 'password' => 'password']);
        $request->server->set('REMOTE_ADDR', '127.0.0.1');

        // hit register API
        $response = $this->authController->register($request);

        // check request type
        $this->assertInstanceOf(JsonResponse::class, $response);

        // check response status code
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());

        // check response data
        $responseData = json_decode($response->getContent(), true);

        // check for required keys in response data
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('statusCode', $responseData);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('accessToken', $responseData);
        $this->assertArrayHasKey('expiresIn', $responseData);
        $this->assertArrayHasKey('user', $responseData);

        // check for static response fields
        $this->assertEquals('User registered successfully', $responseData['message']);
        $this->assertEquals('Bearer', $responseData['tokenType']);

        // check the newly created user data
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Test', $user->first_name);
        $this->assertEquals('User', $user->last_name);
    }

    /**
     * testLogin: Authentication\Http\Controllers\AuthController::login()
     */
    public function testLogin(): void
    {
        // set required data in request to log in a user
        $request = new Request();
        $request->replace(['email' => 'test@example.com', 'password' => 'password']);

        // hit login API
        $response = $this->authController->login($request);

        // check request type
        $this->assertInstanceOf(JsonResponse::class, $response);

        // check response status code
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        // check for required keys in response data
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('statusCode', $responseData);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('accessToken', $responseData);
        $this->assertArrayHasKey('expiresIn', $responseData);
        $this->assertArrayHasKey('user', $responseData);

        // check for static response fields
        $this->assertEquals('Login successful', $responseData['message']);
        $this->assertEquals('Bearer', $responseData['tokenType']);

        // check if $user is a type of User model
        $this->isInstanceOf(User::class, $responseData['user']);

        // check if user data is correct
        $this->assertArrayHasKey('id', $responseData['user']);
        $this->assertEquals('test@example.com', $responseData['user']['email']);
    }

    /**
     * testRefresh: Authentication\Http\Controllers\AuthController::refresh()
     */
    public function testRefresh(): void
    {
        // create new token for this user using same payload
        $response = $this->authController->refresh();

        // check request type
        $this->assertInstanceOf(JsonResponse::class, $response);

        // check response status code
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        // check for required keys in response data
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('statusCode', $responseData);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('accessToken', $responseData);
        $this->assertArrayHasKey('expiresIn', $responseData);
        $this->assertArrayHasKey('user', $responseData);

        // check for static response fields
        $this->assertEquals('Token refreshed', $responseData['message']);
        $this->assertEquals('Bearer', $responseData['tokenType']);
    }

    /**
     * testLogout: Authentication\Http\Controllers\AuthController::logout()
     *
     * I am not testing the token related part since that comes from another library - "zaber04/lumen-api-resources" and I will test that seperately
     */
    public function testLogout(): void
    {
        // Assuming you have a way to authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // Create a request and set client IP
        $request = new Request();
        $request->server->set('REMOTE_ADDR', '127.0.0.1');

        // Call the logout method
        $response = $this->authController->logout($request);

        // Check that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Check that the response status is 200
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        // Check the response data
        $responseData = json_decode($response->getContent(), true);

        // Check for required keys in response data
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('statusCode', $responseData);
        $this->assertArrayHasKey('success', $responseData);

        // Check for static response fields
        $this->assertEquals('Successfully logged out', $responseData['message']);

        // Assert the user is now logged out
        $this->assertFalse(Auth::check());
    }

    /**
     * testMe: Authentication\Http\Controllers\AuthController::me()
     */
    public function testMe(): void
    {
        $response = $this->authController->me();

        $response->assertStatus(200);
        $responseData = json_decode($response->getContent(), true);

        // check if $user is a type of User model
        $this->isInstanceOf(User::class, $responseData);
    }

    /**
     * testWelcome: Authentication\Http\Controllers\AuthController::welcome()
     */
    public function testWelcome(): void
    {
        // create new token for this user using same payload
        $response = $this->authController->welcome();

        // check request type
        $this->assertInstanceOf(JsonResponse::class, $response);

        // check response status code
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        // check for required keys in response data
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('statusCode', $responseData);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertStringContainsString('Welcome to', $response['message']);
    }
}
