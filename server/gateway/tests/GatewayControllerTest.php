<?php

namespace Tests;

use Gateway\Http\Controllers\GatewayController;
use Tests\TestCase;

/**
 * Class GatewayControllerTest.
 *
 * @covers \Gateway\Http\Controllers\GatewayController
 */
final class GatewayControllerTest extends TestCase
{
    private GatewayController $gatewayController;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->gatewayController = new GatewayController();
        $this->app->instance(GatewayController::class, $this->gatewayController);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->gatewayController);
    }

    public function testWelcome(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }

    public function testForwardToAuthService(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }

    public function testForwardToIpHandlerService(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }

    public function testForwardToIpHandler(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }
}
