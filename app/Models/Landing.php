<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Landing extends Model
{
    use HasFactory;

    protected $table = 'landing'; // Specify the table name if it's not the default 'landings'
    protected $fillable = [
        'store',
        'article',
        'title',
        'subtitle',
        'description',
        'text1',
        'text2',
        'text3',
        'img1',
        'img2',
        'img3',
        'img4',
        'link',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public static function where(string $column, $value): Builder
    {
        return parent::query()->where($column, $value);
    }

    protected static function boot():void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now()->addHours(5);
            $model->updated_at = now()->addHours(5);
            $model->link = 'https://my2.mgoods.uz/l/' . $model->article;
        });

        static::updating(function ($model) {
            $model->updated_at = now()->addHours(5);
        });

    }

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'article', 'article');
    }
}
