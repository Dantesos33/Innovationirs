<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    public $timestamps = false;

    protected $table = 'page_views';

    protected $fillable = [
        'url', 'part_id', 'blog_post_id',
        'ip_address', 'user_agent', 'referer', 'session_id',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }
}
