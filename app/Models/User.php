<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $usesUniqueIds = ['username', 'email'];

    public function canAccessPanel(\Filament\Panel $panel): bool
    {

        $allowedPanels = [
            'target' => 'admin',
            'msadmin' => 'landing',
            // Add more user types and their corresponding panels as needed
        ];

        // Check if the user's type has a corresponding panel
        return isset($allowedPanels[$this->type]) && $panel->getId() === $allowedPanels[$this->type] && $this->hasVerifiedEmail();
    }



    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'source', // Add this line
        'store',
        'manager',
        'type',
        'kurs',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getAvailableTypes(): array
    {
        return [
            'target' => 'Target',
            'store' => 'Store',
            'manager' => 'Manager',
            'msadmin' => 'MsAdmin',
            'storekeeper' => 'StoreKeeper',
            'superadmin' => 'SuperAdmin'
        ];
    }

    public static function where(string $column, $value): Builder
    {
        return parent::query()->where($column, $value);
    }

    protected static function boot()
    {
        parent::boot();

        // Automatically set the created_at field when creating a new product
        static::creating(function ($user) {
            $user->created_at = Carbon::now()->addHours(5);
            $user->updated_at = Carbon::now()->addHours(5);
            $user->email_verified_at = session('email_verified_at');
        });

        // Automatically set the updated_at field when updating a product
        static::updating(function ($user) {
            $user->updated_at = Carbon::now()->addHours(5);
        });
    }
}
