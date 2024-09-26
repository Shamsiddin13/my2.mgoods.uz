<?php

namespace App\Models;

use App\Observers\RequestFinObserver;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

#[ObservedBy([RequestFinObserver::class])]
class RequestFin extends Model
{
    use HasFactory;

    use Notifiable;

    protected $table = 'fin_requests';

    protected $primaryKey = 'id';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id',
        'user_type',
        'account',
        'amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'account' => 'integer',
    ];

    public function user() :BelongsTo
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
