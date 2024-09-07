<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
