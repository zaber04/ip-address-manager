<?php

namespace IpHandler\Models;

use Authentication\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Provide history for each ip
 * Chages of this single ip over time by various users
 *
 * // we haven't implement this feature yet
 */
class IpHistory extends Model
{
    use HasUuids; // primary key uuid
    use HasFactory;

    protected $fillable = [
        'ip_address_id',
        'user_id',
        'previous_label',
        'new_label'
    ];

    // user is part of authentication microservice
    protected $localUser;

    public function setUser($user)
    {
        $this->localUser = $user;
        $this->user_id   = $user->id;
    }

    /**
     * Foreign key relationship with 'IpAddress' model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ipAddress()
    {
        return $this->belongsTo(IpAddress::class, 'ip_address_id');
    }

    /**
     * Foreign key relationship with 'User' model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
