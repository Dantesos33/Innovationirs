<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogTag extends Model
{
    protected $table = 'blog_tags';

    protected $fillable = ['name', 'slug'];

    public function posts()
    {
        return $this->belongsToMany(
            BlogPost::class,
            'blog_post_tags',
            'blog_tag_id',
            'blog_post_id'
        );
    }

    protected static function booted(): void
    {
        static::creating(function (BlogTag $tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }
}
