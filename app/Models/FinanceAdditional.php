<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceAdditional extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'fin_additionals';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'for_user',
        'for_user_type',
        'type',
        'amount',
        'description',
    ];

    /**
     * Get the user that owns the FinanceAdditional.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot():void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now()->addHours(5);
            $model->updated_at = now()->addHours(5);
        });

        static::updating(function ($model) {
            $model->updated_at = now()->addHours(5);
        });

    }
}
