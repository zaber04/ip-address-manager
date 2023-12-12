<?php

namespace Gateway\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ErrorLog extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'url',
        'param',
        'body',
        'controller',
        'functionName',
        'statusCode',
        'message',
        'error'
    ];

    // 'ip' is optional
    protected $guarded = ['ip'];
}
