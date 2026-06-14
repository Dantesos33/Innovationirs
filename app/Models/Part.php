<?php
namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// FIX: imported for getCategoryAttribute return type
use Illuminate\Support\Str;

class Part extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'part_number',
        'oem_part_number',
        'sku',
        'make_id',
        'equipment_type_id',
        'condition_type',
        'short_description',
        'description',
        'compatibility_notes',
        'condition_notes',
        'weight_lbs',
        'dimensions',
        'price',
        'sale_price',
        'stock_quantity',
        'stock_status',
        'warranty_type',
        'warranty_notes',
        'primary_image_id',
        'ships_worldwide',
        'is_featured',
        'california_prop65',
        'sample_image_shown',
        'meta_title',
        'meta_description',
        'status',
        'views',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight_lbs' => 'decimal:2',
        'stock_quantity' => 'integer',
        'views' => 'integer',
        'ships_worldwide' => 'boolean',
        'is_featured' => 'boolean',
        'california_prop65' => 'boolean',
        'sample_image_shown' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function make()
    {
        return $this->belongsTo(Make::class, 'make_id');
    }

    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }

    public function primaryImage()
    {
        return $this->belongsTo(MediaLibrary::class, 'primary_image_id');
    }

    public function images()
    {
        return $this->hasMany(PartImage::class, 'part_id')
            ->orderBy('sort_order');
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'part_categories',
            'part_id',
            'category_id'
        )->withPivot('is_primary');
    }

    public function primaryCategory()
    {
        return $this->categories()->wherePivot('is_primary', true)->first();
    }

    public function machineFits()
    {
        return $this->hasMany(PartMachineFit::class, 'part_id');
    }

    public function fitsModels()
    {
        return $this->belongsToMany(
            EquipmentModel::class,
            'part_machine_fits',
            'part_id',
            'model_id'
        )->withPivot('year_start', 'year_end', 'serial_start', 'serial_end', 'fit_notes');
    }

    /**
     * Alias for fitsModels — used by some views/controllers as compatibleModels.
     */
    public function compatibleModels()
    {
        return $this->fitsModels();
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('condition_type', $type);
    }

    public function scopeForMake($query, int $makeId)
    {
        return $query->where('make_id', $makeId);
    }

    public function scopeForEquipmentType($query, int $typeId)
    {
        return $query->where('equipment_type_id', $typeId);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('part_number', 'like', "%{$term}%")
                ->orWhere('oem_part_number', 'like', "%{$term}%")
                ->orWhere('short_description', 'like', "%{$term}%");
        });
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /**
     * Convenience accessor — returns the primary category (or first category).
     * Allows blade views to use $part->category instead of $part->primaryCategory().
     * Requires 'categories' to be eager-loaded.
     */
    public function getCategoryAttribute(): ?Category
    {
        if ($this->relationLoaded('categories')) {
            return $this->categories->firstWhere('pivot.is_primary', true) ?? $this->categories->first();
        }
        return $this->primaryCategory();
    }

    public function getEffectivePriceAttribute(): ?float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null
            && $this->price !== null
            && $this->sale_price < $this->price;
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
        return route('parts.show', [
            'name' => $this->slug,
            'partNumber' => $this->part_number ?? $this->id,
            'id' => $this->id,
        ]);
    }

    public function getWarrantyLabelAttribute(): string
    {
        return match ($this->warranty_type) {
            '30_days' => '30-Day Warranty',
            '90_days' => '90-Day Warranty',
            '6_months' => '6-Month Warranty',
            '1_year' => '1-Year Warranty',
            '2_years' => '2-Year Warranty',
            '3_years' => '3-Year Warranty',
            'custom' => $this->warranty_notes ?? 'Warranty Included',
            default => 'No Warranty',
        };
    }

    public function getStockStatusLabelAttribute(): string
    {
        return match ($this->stock_status) {
            'in_stock' => 'In Stock',
            'out_of_stock' => 'Out of Stock',
            'on_order' => 'On Order',
            'call_for_availability' => 'Call for Availability',
            default => ucfirst(str_replace('_', ' ', $this->stock_status ?? '')),
        };
    }

    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition_type) {
            'new' => 'New',
            'used' => 'Used',
            'rebuilt' => 'Rebuilt',
            'salvage' => 'Salvage',
            default => ucfirst($this->condition_type ?? ''),
        };
    }

    // ─── Boot ─────────────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Part $part) {
            if (empty($part->slug)) {
                $part->slug = Str::slug($part->name);
            }
        });

        static::updating(function (Part $part) {
            if ($part->isDirty('name') && empty($part->slug)) {
                $part->slug = Str::slug($part->name);
            }
        });
    }
}
