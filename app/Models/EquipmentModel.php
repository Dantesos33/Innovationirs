<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EquipmentModel extends Model
{
    use HasFactory;

    protected $table = 'equipment_models';

    protected $fillable = [
        'make_id',
        'name',
        'slug',
        'year_start',
        'year_end',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'year_start' => 'integer',
        'year_end'   => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function make()
    {
        return $this->belongsTo(Make::class, 'make_id');
    }

    public function parts()
    {
        return $this->belongsToMany(
            Part::class,
            'part_machine_fits',
            'model_id',
            'part_id'
        )->withPivot('year_start', 'year_end', 'serial_start', 'serial_end', 'fit_notes');
    }

    public function machineFits()
    {
        return $this->hasMany(PartMachineFit::class, 'model_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForMake($query, int $makeId)
    {
        return $query->where('make_id', $makeId);
    }

    // ─── Accessors ────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return $this->make->name . ' ' . $this->name;
    }

    public function getYearRangeAttribute(): string
    {
        if ($this->year_start && $this->year_end) {
            return $this->year_start . '–' . $this->year_end;
        }
        if ($this->year_start) {
            return $this->year_start . '–Present';
        }
        return '';
    }

    // ─── Boot ─────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (EquipmentModel $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
