<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
//    use HasFactory;

    // Specify the table name if it's not the default 'otps'
    protected $table = 'otps';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'email',
        'otp_code',
        'expires_at',
    ];

    // Cast 'expires_at' as a timestamp
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Check if the OTP is still valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->expires_at->isFuture();
    }

    /**
     * Define the relationship to the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function where(string $column, $value): Builder
    {
        return parent::query()->where($column, $value);
    }

    protected static function boot()
    {
        parent::boot();

        // Automatically set the created_at field when creating a new product
        static::creating(function ($otp) {
            $otp->created_at = Carbon::now()->addHours(5);
            $otp->updated_at = Carbon::now()->addHours(5);
        });

        static::updating(function ($user) {
            $user->updated_at = Carbon::now()->addHours(5);
        });
    }

}
