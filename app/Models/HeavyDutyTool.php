<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HeavyDutyTool extends Model
{
    use HasFactory;

    protected $table = 'heavy_duty_tools';

    protected $fillable = [
        'name', 'slug', 'sku', 'part_number',
        'short_description', 'description', 'specifications',
        'price', 'sale_price',
        'stock_quantity', 'stock_status', 'status',
        'primary_image_id',
        'is_featured', 'ships_worldwide',
        'weight_lbs', 'dimensions',
        'brand', 'model_number',
        'meta_title', 'meta_description',
        'views', 'sort_order',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'sale_price'     => 'decimal:2',
        'weight_lbs'     => 'decimal:2',
        'stock_quantity' => 'integer',
        'views'          => 'integer',
        'sort_order'     => 'integer',
        'is_featured'    => 'boolean',
        'ships_worldwide'=> 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function primaryImage()
    {
        return $this->belongsTo(MediaLibrary::class, 'primary_image_id');
    }

    public function images()
    {
        return $this->hasMany(HeavyDutyToolImage::class, 'tool_id')->orderBy('sort_order');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('sku', 'like', "%{$term}%")
              ->orWhere('part_number', 'like', "%{$term}%")
              ->orWhere('brand', 'like', "%{$term}%")
              ->orWhere('short_description', 'like', "%{$term}%");
        });
    }

    // ─── Accessors ────────────────────────────────────────────────────

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->price);
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null
            && (float) $this->sale_price < (float) $this->price;
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->primaryImage) {
            return $this->primaryImage->public_url;
        }
        return asset('images/placeholder-part.jpg');
    }

    public function getUrlAttribute(): string
    {
        return route('tools.show', $this->slug);
    }

    public function getStockStatusLabelAttribute(): string
    {
        return match ($this->stock_status) {
            'in_stock'    => 'In Stock',
            'out_of_stock'=> 'Out of Stock',
            'on_order'    => 'On Order',
            default       => ucfirst($this->stock_status ?? ''),
        };
    }

    // ─── Boot ─────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (HeavyDutyTool $tool) {
            if (empty($tool->slug)) {
                $tool->slug = Str::slug($tool->name);
            }
        });

        static::updating(function (HeavyDutyTool $tool) {
            if ($tool->isDirty('name') && empty($tool->slug)) {
                $tool->slug = Str::slug($tool->name);
            }
        });
    }
}
