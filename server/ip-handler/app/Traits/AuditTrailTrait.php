<?php

declare(strict_types=1);

namespace IpHandler\Traits;

use Gateway\Enums\ActionEnum;
// use Gateway\Traits\ExceptionHandlerTrait;
use IpHandler\Models\AuditTrail;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

trait AuditTrailTrait
{
    // use ExceptionHandlerTrait;

    // NO INTERNAL ERROR HANDLING --> Haandled as transaction in controller during usage
    private function storeAuditEvent(Request $request, string $propertyId, ActionEnum $eventType = ActionEnum::UPDATE, string $tableName = 'ip_addresses') {
        // Extract JWT token from the Authorization header and
        // get session id and user id for this session
        $authHeader = $request->header('Authorization');
        list($tokenType, $token) = explode(' ', $authHeader);

        // Decode and validate the token
        $decodedToken = JWTAuth::decode($token);

        // Access user_id from the token payload
        $userId    = $decodedToken['user_id'] ?? '';
        $sessionId = $decodedToken['session_id'] ?? '';

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
