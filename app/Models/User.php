<?php

namespace App\Models;

use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $usesUniqueIds = ['username', 'email'];


    public function canAccessPanel(Panel $panel): bool
    {

        $allowedPanels = [
            'target' => 'admin',
            'store' => 'store',
            'msadmin' => 'landing',
            'manager' => 'manager',
            'storekeeper' => 'storekeeper',
            'superadmin' => 'superadmin',
            // Add more user types and their corresponding panels as needed
        ];

        // Check if the user's type has a corresponding panel
        session(['panel_id' => $panel->getId()]);
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
        'usd_exchange_rate',
        'avatar_url',
        'telegram_id'
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
//    public function getFilamentAvatarUrl(): ?string
//    {
//        return $this->avatar_url ? Storage::url("$this->avatar_url") : null;
//    }

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
            'superadmin' => '密碼'
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
            if ($user->email === null) {
                $user->email = 'email_unavailable_' . $user->username . '@gmail.com';
                $user->email_verified_at = Carbon::now()->addHours(5);
            }
            $user->created_at = Carbon::now()->addHours(5);
            $user->updated_at = Carbon::now()->addHours(5);
            $user->email_verified_at = session('email_verified_at');

            // Create a new Unique_Link for the new user
            UniqueLink::create([
                'username' => $user->username,
                'unique_parameter' => uuid_create(),
                'created_at' => Carbon::now()->addHours(5),
                'updated_at' => Carbon::now()->addHours(5),
                'is_used' => false
            ]);
        });

        // Automatically set the updated_at field when updating a product
        static::updating(function ($user) {
            $user->updated_at = Carbon::now()->addHours(5);
        });
    }
}
