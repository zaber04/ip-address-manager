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
    private function storeAuditEvent(Request $request, string $propertyId, string $previousValue = "", ActionEnum $eventType = ActionEnum::UPDATE, string $tableName = 'ip_addresses') {
        // get the jwt token
        $tokenArray = $this->getTokenArrayFromHeader($request);

        // Access user_id & session_id from the token payload
        $client_ip = $request->getClientIp();
        $userId    = $tokenArray['user_id'] ?? $tokenArray['user']['user_id']  ?? $tokenArray['sub'] ?? $client_ip;
        $sessionId = $tokenArray['session_id'] ?? $tokenArray['user']['session_id'] ?? $userId;

        // Create a new AuditTrail instance with user id and user ip
        $auditTrail = new AuditTrail([
            'action'        => $eventType,
            'property_name' => AuditTrail::PROPERTY_NAME,
            'old_data'      => $previousValue,
            'new_data'      => $request->input('label'),
            'user_id'       => $userId,
            'session_id'    => $sessionId,
            'property_id'   => $propertyId, // stores the uuid of the entry IpAdress
            'table_updated' => $tableName, //?
            'user_ip'       => $client_ip
        ]);

        $auditTrail->save();
    }
}
