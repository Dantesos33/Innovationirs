<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image_media_id',
        'description',
        'meta_title',
        'meta_description',
        'is_active',
        'is_featured',
        'sort_order',
        'parts_count',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'sort_order'  => 'integer',
        'parts_count' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function image()
    {
        return $this->belongsTo(MediaLibrary::class, 'image_media_id');
    }

    /**
     * Alias for image() — used in views as $cat->image_media
     */
    public function image_media()
    {
        return $this->belongsTo(MediaLibrary::class, 'image_media_id');
    }
    public function parts()
    {
        return $this->belongsToMany(
            Part::class,
            'part_categories',
            'category_id',
            'part_id'
        )->withPivot('is_primary');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
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
        return asset('images/categories/' . $this->slug . '.png');
    }

    public function getUrlAttribute(): string
    {
        return route('categories.show', $this->slug);
    }

    // ─── Boot ─────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
