<?php

namespace Tests;

use IpHandler\Http\Controllers\AuditTrailController;
use Tests\TestCase;

/**
 * Class AuditTrailControllerTest.
 *
 * @covers \IpHandler\Http\Controllers\AuditTrailController
 */
final class AuditTrailControllerTest extends TestCase
{
    private AuditTrailController $auditTrailController;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->auditTrailController = new AuditTrailController();
        $this->app->instance(AuditTrailController::class, $this->auditTrailController);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->auditTrailController);
    }

    public function testIndex(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }

    public function testShowByUserId(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }

    public function testShowByAuditId(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->seeStatusCode(200);
    }
}
