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
        'label'    => 'required|string|min:1|max:255',
    ];

    protected $maxLabelLength = 255;

    // we need a boolean field 'archive' wtih default false providing soft delete feature

    /**
     * The validation messages for the model.
     *
     * @var array
     */
    protected $messages = [
        'ip.ip' => 'The IP address must be a valid IPv4 or IPv6 address.',
    ];

    /**
     * Get the rules for validation.
     *
     * @return array
     */
    public static function getValidationRules(): array
    {
        return self::$rules;
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    public static function getValidationMessages(): array
    {
        return self::$messages;
    }

    /**
     * Get the maximum length for the label.
     *
     * @return int
     */
    public function getMaxLabelLength(): int
    {
        return $this->maxLabelLength;
    }

    /**
     * Validate the given data against the IpAddress model rules.
     *
     * @param  array  $data
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validate(array $data): void
    {
        validator($data, static::$rules)->validate();
    }
}
