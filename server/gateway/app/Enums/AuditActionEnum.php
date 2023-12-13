<?php

namespace Gateway\Enums;

/**
 * Allowed actions
 *
 * // For advanced enum implimentation, check --> bensampo/laravel-enum package
 */
enum AuditActionEnum:string {
    case ARCHIVE = 'archive';
    case DELETE  = 'delete';
    case INSERT  = 'insert';
    case UPDATE  = 'update';
}
