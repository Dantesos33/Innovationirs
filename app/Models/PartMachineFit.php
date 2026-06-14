<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartMachineFit extends Model
{
    use HasFactory;

    protected $table = 'part_machine_fits';

    protected $fillable = [
        'part_id',
        'model_id',
        'year_start',
        'year_end',
        'serial_start',
        'serial_end',
        'fit_notes',
    ];

    protected $casts = [
        'year_start' => 'integer',
        'year_end'   => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    public function model()
    {
        return $this->belongsTo(EquipmentModel::class, 'model_id');
    }

    // ─── Accessors ────────────────────────────────────────────────────

    /**
     * Returns a human-readable year range, e.g. "2005–2012" or "2010+".
     */
    public function getYearRangeAttribute(): ?string
    {
        if (! $this->year_start && ! $this->year_end) {
            return null;
        }
        if ($this->year_start && $this->year_end) {
            return $this->year_start === $this->year_end
                ? (string) $this->year_start
                : "{$this->year_start}–{$this->year_end}";
        }
        return $this->year_start ? "{$this->year_start}+" : "Up to {$this->year_end}";
    }
}
