<?php

namespace IpHandler\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class IpAddress extends Model
{
    use HasUuids; // primary key uuid
    use HasFactory;

    protected $fillable = [
        'ip', 'label'
    ];

    public static $rules = [
        'ip'       => 'required|ip',
        'label'    => 'required|string|max:255',
    ];

    // we need a boolean field 'archive' wtih default false providing soft delete feature

    /**
     * The validation messages for the model.
     *
     * @var array
     */
    public static $messages = [
        'ip.ip' => 'The IP address must be a valid IPv4 or IPv6 address.',
    ];
}
