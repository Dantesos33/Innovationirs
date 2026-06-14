<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EquipmentType extends Model
{
    use HasFactory;

    protected $table = 'equipment_types';

    protected $fillable = [
        'name',
        'slug',
        'image_media_id',
        'description',
        'meta_title',
        'meta_description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function image()
    {
        return $this->belongsTo(MediaLibrary::class, 'image_media_id');
    }

    /**
     * Alias for image() — used in views as $type->image_media
     */
    public function image_media()
    {
        return $this->belongsTo(MediaLibrary::class, 'image_media_id');
    }

    public function parts()
    {
        return $this->hasMany(Part::class, 'equipment_type_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // ─── Accessors ────────────────────────────────────────────────────

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return $this->image->public_url;
        }
        return asset('images/equipment/' . $this->slug . '.jpg');
    }

    // ─── Boot ─────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (EquipmentType $type) {
            if (empty($type->slug)) {
                $type->slug = Str::slug($type->name);
            }
        });
    }
}
