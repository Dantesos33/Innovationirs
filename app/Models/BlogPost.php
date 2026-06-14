<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = 'blog_posts';

    protected $fillable = [
        'admin_id', 'blog_category_id', 'featured_image_id',
        'title', 'slug', 'excerpt', 'content', 'read_time_minutes',
        'status', 'published_at',
        'meta_title', 'meta_description', 'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views'        => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function author()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function featuredImage()
    {
        return $this->belongsTo(MediaLibrary::class, 'featured_image_id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            BlogTag::class,
            'blog_post_tags',
            'blog_post_id',
            'blog_tag_id'
        );
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('blog_category_id', $categoryId);
    }

    public function scopeByTag($query, int $tagId)
    {
        return $query->whereHas('tags', fn($q) => $q->where('blog_tags.id', $tagId));
    }

    // ─── Accessors ────────────────────────────────────────────────────

    public function getFeaturedImageUrlAttribute(): string
    {
        return $this->featuredImage?->public_url ?? asset('images/placeholder-blog.jpg');
    }

    public function getUrlAttribute(): string
    {
        return route('blog.show', [$this->slug, $this->id]);
    }

    public function getExcerptOrGeneratedAttribute(): string
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }

        return Str::limit(strip_tags($this->content), 160);
    }

    // ─── Boot ─────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (BlogPost $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if (empty($post->read_time_minutes)) {
                $words                   = str_word_count(strip_tags($post->content));
                $post->read_time_minutes = (int) ceil($words / 200);
            }
        });
    }
}
