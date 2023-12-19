<?php

namespace Tests;

use IpHandler\Http\Controllers\IpHandlerController;
use Tests\TestCase;

/**
 * Class IpHandlerControllerTest.
 *
 * @covers \IpHandler\Http\Controllers\IpHandlerController
 */
final class IpHandlerControllerTest extends TestCase
{
    private IpHandlerController $ipHandlerController;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->ipHandlerController = new IpHandlerController();
        $this->app->instance(IpHandlerController::class, $this->ipHandlerController);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->ipHandlerController);
    }

    public function testIndex(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }

    public function testStore(): void
    {
        /** @todo This test is incomplete. */
        $this->post('/path', [ /* data */ ])
            ->seeStatusCode(200);
    }

    public function testShow(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }

    public function testUpdate(): void
    {
        /** @todo This test is incomplete. */
        $this->put('/path', [ /* data */ ])
            ->seeStatusCode(200);
    }

    public function testWelcome(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }
}
