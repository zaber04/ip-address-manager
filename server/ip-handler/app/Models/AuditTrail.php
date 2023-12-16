<?php

namespace IpHandler\Models;

use Gateway\Enums\ActionEnum;
use Authentication\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditTrail extends Model
{
    use HasUuids; // primary key uuid
    use HasFactory;

    public const PROPERTY_NAME = 'IP Address';

    // session id helps us filter actions of a user across the session
    protected $fillable = ['action', 'property_name', 'old_data', 'new_data', 'user_id',  'session_id', 'user_ip', 'property_id', 'table_updated'];

    /**
     * Cast to native types.
     */
    protected $casts = [
        'old_data' => 'json',
        'new_data' => 'json',
        'action'   => ActionEnum::class
    ];

    /**
     * Rules
     */
    public static $rules = [
        'action'        => ['required', 'string', 'enum: ' . ActionEnum::class],
        'property_name' => 'required|string',
        'old_data'      => 'nullable|string|json',
        'new_data'      => 'nullable|string|json',
        'user_id'       => 'required|exists:users,id',
        'session_id'    => 'nullable|uuid'
    ];


    /**
     * Define the relationship with the 'User' model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
