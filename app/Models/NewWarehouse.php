<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewWarehouse extends Model
{
    use HasFactory;

    protected $table = 'new_warehouse';
    protected $fillable = [
        'user_id',
        'total_quantity',
        'total_summ',
        'created_at',
        'updated_at'
    ];

    // Define the casts for the timestamps
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Define the relationship to the User model
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details():HasMany
    {
        return $this->hasMany(WarehouseDetails::class);
    }
    protected static function boot():void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now()->addHours(5);
            $model->updated_at = now()->addHours(5);

            $totalPrice = WarehouseDetails::where('warehouse_id', $model->id)->sum('total_price');
            $totalQuantity = WarehouseDetails::where('warehouse_id', $model->id)->sum('quantity');
            $model->total_summ = $totalPrice;
            $model->total_quantity = $totalQuantity;
        });

        static::updating(function ($model) {
            $model->updated_at = now()->addHours(5);
        });

    }
}
