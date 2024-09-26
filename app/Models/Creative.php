<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Creative extends Model
{
    use HasFactory;

    protected $table = 'creative';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'article',
        'title',
        'description',
        'video',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'user_id' => 'integer:20',
        'account' => 'integer',
        'amount' => 'decimal:2',
    ];


    public function user() : BelongsTo
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
