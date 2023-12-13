<?php

namespace IpHandler\Models;

use Gateway\Enums\AuditActionEnum;
use Authentication\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditTrail extends Model
{
    use HasUuids; // primary key uuid
    use HasFactory;

    protected $fillable = ['action', 'property_name', 'old_data', 'new_data', 'user_id'];
    protected $guarded  = ['property_id', 'table_updated'];

    /**
     * Cast to native types.
     */
    protected $casts = [
        'old_data' => 'json',
        'new_data' => 'json',
        'action'   => AuditActionEnum::class
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
