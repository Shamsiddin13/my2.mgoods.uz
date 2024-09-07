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
    public static function getOrderStatistics($source, $fromDate, $untilDate)
    {
        return static::selectRaw("
            article,
            COUNT(DISTINCT CASE WHEN status IN ('Новый', 'Принят', 'Недозвон', 'Отмена', 'В пути', 'Доставлен', 'Выполнен', 'Возврат', 'Подмены') THEN ID_number ELSE NULL END) AS Lead,
            COUNT(DISTINCT CASE WHEN status IN ('Принят') THEN ID_number ELSE NULL END) AS Qabul,
            COUNT(DISTINCT CASE WHEN status IN ('Отмена') THEN ID_number ELSE NULL END) AS Otkaz,
            COUNT(DISTINCT CASE WHEN status IN ('В пути', 'EMU') THEN ID_number ELSE NULL END) AS Yolda,
            COUNT(DISTINCT CASE WHEN status IN ('Доставлен') THEN ID_number ELSE NULL END) AS Yetkazildi,
            COUNT(DISTINCT CASE WHEN status IN ('Выполнен') THEN ID_number ELSE NULL END) AS Sotildi,
            COUNT(DISTINCT CASE WHEN status IN ('Возврат') THEN ID_number ELSE NULL END) AS QaytibKeldi
        ")
            ->where('source', '=', $source)
            ->whereBetween('createdAt', [$fromDate, $untilDate])
            ->groupBy('article')
            ->orderBy('article', 'DESC');
    }


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
