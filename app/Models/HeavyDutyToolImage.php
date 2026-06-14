<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeavyDutyToolImage extends Model
{
    protected $table = 'heavy_duty_tool_images';

    protected $fillable = [
        'tool_id',
        'media_id',
        'sort_order',
        'alt_text',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function tool()
    {
        return $this->belongsTo(HeavyDutyTool::class, 'tool_id');
    }

    public function media()
    {
        return $this->belongsTo(MediaLibrary::class, 'media_id');
    }

    public function getPublicUrlAttribute(): string
    {
        return $this->media?->public_url ?? asset('images/placeholder-part.jpg');
    }
}
