<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartImage extends Model
{
    public $timestamps = false;

    protected $table = 'part_images';

    protected $fillable = [
        'part_id', 'media_id', 'is_primary', 'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function media()
    {
        return $this->belongsTo(MediaLibrary::class, 'media_id');
    }

    /**
     * ->url accessor — returns the public URL of the associated media file.
     */
    public function getUrlAttribute(): string
    {
        return $this->media?->public_url ?? asset('images/placeholder-part.jpg');
    }

    /**
     * ->public_url accessor — alias of ->url so Blade views can call either.
     * The show.blade.php calls $part->images->first()->public_url, which
     * requires this accessor to exist on PartImage (not just on MediaLibrary).
     */
    public function getPublicUrlAttribute(): string
    {
        return $this->getUrlAttribute();
    }
}
