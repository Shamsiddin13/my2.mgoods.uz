<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Notification extends Model
{
    use HasFactory, Notifiable;

    // Define the table associated with the model
    protected $table = 'notifications';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Disable Laravel's timestamps if not required
    public $timestamps = true;

    protected $keyType = 'string';

    public $incrementing = false;

    // Define which attributes can be mass-assigned
    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at'
    ];

    // Define the attributes that should be mutated to dates
    protected $dates = [
        'read_at',
        'created_at',
        'updated_at'
    ];

    // Define the data attribute as an array
    protected $casts = [
        'data' => 'array'
    ];

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    protected static function boot():void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now()->addHours(5);
            $model->updated_at = now()->addHours(5);
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });

        static::updating(function ($model) {
            $model->updated_at = now()->addHours(5);
        });

    }
}
