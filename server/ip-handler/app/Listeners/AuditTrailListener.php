<?php

declare(strict_types=1);

namespace IpHandler\Listeners;

use Authentication\Events\UserEvent;
use IpHandler\Models\AuditTrail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;


class AuditTrailListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserEvent $event): void
    {
        try {
            // @TODO: keep sessionid in cache and use it for this session
            // or we can keep inside jwt and use jwt decoding

            // Create a new AuditTrail instance with user id and user ip
            $auditTrail = new AuditTrail([
                'action'        => $event->eventType,
                'property_name' => AuditTrail::PROPERTY_NAME,
                'old_data'      => '',
                'new_data'      => "Login time: " . $event->timestamp,
                'user_id'       => $event->userId,
                'session_id'    => $event->sessionId,
                'user_ip'       => $event->ip
            ]);

            $auditTrail->save();
        } catch (\Exception $e) {
            Log::error('Failed to store login event in db. Event data: ' . json_encode($event) . " Error message: " . $e->getMessage());
        }
    }
}
