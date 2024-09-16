<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Stream extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural of the model name
    protected $table = 'stream';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Specify if the primary key is auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // If the table doesn't have timestamps (created_at, updated_at), set this to false
    public $timestamps = false;

    // If you do have timestamps but they have custom names, define them
    const CREATED_AT = 'createdAt';

    // Define the fillable attributes (columns that can be mass-assigned)
    protected $fillable = [
        'stream_name',
        'source',
        'link',
        'full_link',
        'pixel_id',
        'landing_id',
        'createdAt',
        // Add other columns if necessary
    ];

    // Define any relationships

    public function product()
    {
        return $this->hasOne(Product::class, 'article', 'article');
    }

//    public static function query(): Builder
//    {
//        return parent::query()->with('product'); // Ensure this matches the relationship method in the model
//    }
    public function landing()
    {
        return $this->belongsTo(Landing::class, 'landing_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now()->addHours(5);
        });

        static::creating(function ($stream) {
            $userId = auth()->id(); // Get the currently authenticated user's ID

            // Find the warehouse associated with this user
            $user = User::where('id', $userId)->first();

            if ($user) {
                $stream->source = $user->source;
            } else {
                // Handle cases where no warehouse is found
                throw new Exception('No warehouse found for the logged-in user.');
            }
        });

        static::creating(function ($stream) {
            // Generate a unique short link
            $stream->link = Str::random(6);

            // Update the full_link with the generated link
            $stream->full_link = url('/l/' . $stream->link);
        });

        static::updating(function ($stream) {
            // If you want to regenerate the link and full_link on update:
            $stream->link = Str::random(6);
            $stream->full_link = url('/l/' . $stream->link);
        });
    }

    public static function where(string $column, $value): Builder
    {
        return parent::query()->where($column, $value);
    }
}
