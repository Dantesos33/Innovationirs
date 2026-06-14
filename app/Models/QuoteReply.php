<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteReply extends Model
{
    protected $table = 'quote_replies';

    protected $fillable = [
        'quote_id', 'admin_id', 'message',
        'is_admin', 'email_sent', 'email_sent_at',
    ];

    protected $casts = [
        'is_admin'      => 'boolean',
        'email_sent'    => 'boolean',
        'email_sent_at' => 'datetime',
    ];

    public function quote()
    {
        return $this->belongsTo(QuoteRequest::class, 'quote_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
