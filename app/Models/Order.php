<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;
    

    // If the table name does not follow Laravel's naming convention,
    // explicitly set the table name
    protected $table = 'orders';

    // Since your table doesn't have an `id` column as the primary key,
    // define the primary key if it's not the default `id`
    protected $primaryKey = 'ID_number';
    public $timestamps = false;
    // If the primary key is not an auto-incrementing integer, specify its type
    public $incrementing = false;
    protected $keyType = 'string';

    // Define the columns that can be mass-assigned
    protected $fillable = [
        'ID_number',
        'createdAt',
        'displayProductName',
        'article',
        'statusUpdatedAt',
        'summ',
        'totalSumm',
        'prepaySum',
        'quantity',
        'purchaseSumm',
        'store',
        'source',
        'medium',
        'campaign',
        'status',
        'manager',
        'costShip',
        'netCostShip',
        'target',
    ];

    // If you want to use the createdAt and statusUpdatedAt columns as timestamps,
    // specify the custom names for the timestamp columns
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'statusUpdatedAt';

    // Optionally, specify any casts for your columns
    protected $casts = [
        'createdAt' => 'datetime',
        'statusUpdatedAt' => 'datetime',
        'summ' => 'decimal:2',
        'totalSumm' => 'decimal:2',
        'prepaySum' => 'decimal:2',
        'quantity' => 'integer',
        'purchaseSumm' => 'decimal:2',
        'costShip' => 'decimal:2',
        'netCostShip' => 'decimal:2',
        'target' => 'decimal:2',
    ];
    // Order Model (Order.php)
    private static function select(string $column): Builder
    {
        return self::query()->select($column);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'article', 'article');
    }

    // In Order.php
    public function user()
    {
        return $this->belongsTo(User::class, 'source', 'source');
    }

    public static function getAllArticleGroupedBy()
    {
        return self::select('article')
            ->whereNotNull('article')
            ->where('article', '!=', '')
            ->where('article', '!=', 'None')
            ->groupBy('article')
            ->get();
    }

}
