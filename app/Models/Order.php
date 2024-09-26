<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
        'payForWeb',
        'pvz',
        'free1',
        'free2',
        'two_plus_one',
        'link',
        'orderMethod',
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

    public static function getAllArticleGroupedBy($column, $source)
    {
        return self::select('article')
            ->whereNotNull('article')
            ->where('article', '!=', '')
            ->where('article', '!=', 'None')
            ->where($column, $source)
            ->groupBy('article')
            ->orderBy('article')
            ->get();
    }

    public static function getAllProductNameGroupedBy($column, $source)
    {
        return self::select('displayProductName')
            ->whereNotNull('displayProductName')
            ->where('displayProductName', '!=', '')
            ->where('displayProductName', '!=', 'None')
            ->where($column, $source)
            ->groupBy('displayProductName')
            ->orderBy('displayProductName')
            ->get();
    }

    public function scopeWithAggregates($query, $source)
    {
        return $query
            ->selectRaw("
                DATE(createdAt) AS createdAt,
                displayProductName,
                COUNT(DISTINCT CASE WHEN status IN (
                    'new', 'updated', 'recall', 'call_late', 'cancel', 'accept',
                    'send', 'delivered', 'returned', 'sold'
                ) THEN ID_number ELSE NULL END) AS Lead,
                COUNT(DISTINCT CASE WHEN status = 'accept' THEN ID_number ELSE NULL END) AS Qabul,
                COUNT(DISTINCT CASE WHEN status = 'cancel' THEN ID_number ELSE NULL END) AS Otkaz,
                COUNT(DISTINCT CASE WHEN status = 'send' THEN ID_number ELSE NULL END) AS Yolda,
                COUNT(DISTINCT CASE WHEN status = 'delivered' THEN ID_number ELSE NULL END) AS Yetkazildi,
                COUNT(DISTINCT CASE WHEN status = 'sold' THEN ID_number ELSE NULL END) AS Sotildi,
                COUNT(DISTINCT CASE WHEN status = 'returned' THEN ID_number ELSE NULL END) AS QaytibKeldi
            ")
            ->where('source', $source)
            ->groupBy('createdAt', 'displayProductName');
    }

}
