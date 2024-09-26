<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniqueLink extends Model
{
    protected $table = 'unique_links';  // Define the table associated with the model

    protected $fillable = ['username', 'unique_parameter', 'is_used'];  // Allow mass assignment for these fields

    // You might want to cast 'is_used' to boolean for easier handling in PHP
    protected $casts = [
        'is_used' => 'boolean',
    ];

    // Define the date attributes to ensure they are returned as Carbon instances
    protected $dates = ['created_at'];

    // Optionally, define relationship with User model if it exists
    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}
