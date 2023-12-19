<?php

namespace Tests;

use Authentication\Http\Controllers\AuthController;
use Tests\TestCase;

/**
 * Class AuthControllerTest.
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

    public function testRegister(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }

    public function testLogin(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }

    public function testRefresh(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }

    public function testLogout(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }

    public function testMe(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }

    public function testWelcome(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }
}
