<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Make extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo_media_id',
        'description',
        'meta_title',
        'meta_description',
        'is_active',
        'sort_order',
        'parts_count',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
        'parts_count' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function logo()
    {
        return $this->belongsTo(MediaLibrary::class, 'logo_media_id');
    }

    public function models()
    {
        return $this->hasMany(EquipmentModel::class, 'make_id');
    }

    public function parts()
    {
        return $this->hasMany(Part::class, 'make_id');
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

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return $this->logo->public_url;
        }
        return asset('images/placeholder-logo.png');
    }

    public function getUrlAttribute(): string
    {
        return route('makes.show', $this->slug);
    }

    // ─── Boot ─────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Make $make) {
            if (empty($make->slug)) {
                $make->slug = Str::slug($make->name);
            }
        });

        static::updating(function (Make $make) {
            if ($make->isDirty('name') && empty($make->slug)) {
                $make->slug = Str::slug($make->name);
            }
        });
    }
}
