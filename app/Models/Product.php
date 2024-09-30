<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Specify the table name if it's not the default 'products'
    protected $table = 'products';

    protected $primaryKey = 'article';
    // Specify the primary key type
    protected $keyType = 'string';
    public $incrementing = false; // UUIDs are not auto-incrementing

    // Define the fillable properties
    protected $fillable = [
        'id',
        'name',
        'image',
        'buyPrice',
        'salePrice',
        'article',
        'income_quantity',
        'store',
        'status',
        'target',
        'two_plus_one',
        'free2',
        'free1',
        'pvz',
        'last_updatedAt',
    ];

    // Specify any casts for your columns
    protected $casts = [
        'target' => 'decimal:2',
        'last_updatedAt' => 'timestamp',
    ];
    public function imageUrl()
    {
        // Construct the path to the image
        $imagePath = "/storage/product_images/{$this->id}.jpg";

        // Check if the file exists (optional, if you want to validate)
        if (file_exists(public_path($imagePath))) {
            return $imagePath;
        }

        // Return default image path if the specific image does not exist
        return '/storage/images/noimage.jpg';
    }
    public function getLandingImageAttribute()
    {
        return $this->landingData?->img1;
    }
    public function getLandingIdAttribute()
    {
        return $this->landingData?->id;
    }
    public function getLandingTitleAttribute()
    {
        return $this->landingData?->title;
    }

    public function getLandingLinkAttribute()
    {
        return $this->landingData?->link;
    }

    public function getLandingCreatedAtAttribute()
    {
        return $this->landingData?->created_at;
    }

    public function getLandingDescriptionAttribute()
    {
        return $this->landingData?->description;
    }

    public function getLandingDataAttribute()
    {
        return Landing::where('article', $this->article)->first();
    }

    public static function where(string $column, $value): Builder
    {
        return parent::query()->where($column, $value);
    }

    private static function select(string $column): Builder
    {
        return self::query()->select($column);
    }
    public static function getAllStoresGroupedBy()
    {
        return self::select('store')
            ->whereNotNull('store')
            ->where('store', '!=', '')
            ->where('store', '!=', ' ')
            ->where('store', '!=', 'NULL')
            ->where('store', '!=', 'None')
            ->groupBy('store')
            ->get();
    }

    public static function getAllArticleGroupedBy()
    {
        return self::select('article')
            ->whereNotNull('article')
            ->where('article', '!=', 'null')
            ->where('article', '!=', 'None')
            ->groupBy('article')
            ->get();
    }
}
