<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseDetails extends Model
{
    use HasFactory;

    protected $table = 'warehouse_details';

    // Define the fillable properties
    protected $fillable = [
        'warehouse_id',
        'article',
        'product_name',
        'store',
        'user_id',
        'total_price',
        'type',
        'quantity',
        'comment',
        'created_at',
        'updated_at'
    ];

    // Define the default values for the fields (optional)
    protected $attributes = [
        'comment' => null,
    ];

    // Define casts if necessary
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'quantity' => 'integer',
        'type' => 'string',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define the relationship to the Warehouse (new_warehouse) table
    public function warehouse()
    {
        return $this->belongsTo(NewWarehouse::class, 'warehouse_id');
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
