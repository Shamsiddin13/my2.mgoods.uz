<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersReport extends Model
{
    use HasFactory;

    protected $table = 'users_reports';  // Define the table associated with the model

    protected $fillable = ['username', 'user_id', 'paid', 'hold', 'total', 'balance', 'user_type'];  // Allow mass assignment for these fields
    protected $dates = ['last_updated_at'];

    public $timestamps = false;

    // Optionally, define relationship with User model if it exists
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
