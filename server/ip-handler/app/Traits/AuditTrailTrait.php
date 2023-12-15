<?php

declare(strict_types=1);

namespace IpHandler\Traits;

use Authentication\Traits\TokenTrait;
use Gateway\Enums\ActionEnum;
use IpHandler\Models\AuditTrail;

use Illuminate\Http\Request;

trait AuditTrailTrait
{
    use TokenTrait;

    // NO INTERNAL ERROR HANDLING --> Haandled as transaction in controller during usage
    private function storeAuditEvent(Request $request, string $propertyId, ActionEnum $eventType = ActionEnum::UPDATE, string $tableName = 'ip_addresses') {
        // get the jwt token
        $tokenArray = $this->getTokenArrayFromHeader($request);

        // Access user_id & session_id from the token payload
        $userId    = $tokenArray['user_id'] ?? $tokenArray['sub'] ?? '';
        $sessionId = $tokenArray['session_id'] ?? '';

        // Create a new AuditTrail instance with user id and user ip
        $auditTrail = new AuditTrail([
            'action'        => $eventType,
            'property_name' => AuditTrail::PROPERTY_NAME,
            'old_data'      => '',
            'new_data'      => $request->input('label'),
            'user_id'       => $userId,
            'session_id'    => $sessionId,
            'property_id'   => $propertyId, // stores the uuid of the entry IpAdress
            'table_updated' => $tableName, //?
            'user_ip'       => $request->getClientIp()
        ]);

        $auditTrail->save();
    }
}
