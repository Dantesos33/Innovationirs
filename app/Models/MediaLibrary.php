<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class MediaLibrary extends Model
{
    use HasFactory;

    protected $table = 'media_library';

    protected $fillable = [
        'admin_id',
        'disk',
        'directory',
        'filename',
        'original_name',
        'file_path',
        'url',
        'mime_type',
        'extension',
        'file_size',
        'width',
        'height',
        'alt_text',
        'title',
        'caption',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'width'     => 'integer',
        'height'    => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function uploader()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    // ─── Accessors ────────────────────────────────────────────────────

    public function getPublicUrlAttribute(): string
    {
        if ($this->url) {
            return $this->url;
        }
        return Storage::disk($this->disk)->url($this->file_path);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    public function scopeInDirectory($query, string $directory)
    {
        return $query->where('directory', $directory);
    }
}
