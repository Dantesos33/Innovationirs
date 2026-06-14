<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactReply extends Model
{
    protected $table = 'contact_replies';

    protected $fillable = [
        'contact_id', 'admin_id', 'message',
        'is_admin', 'email_sent', 'email_sent_at',
    ];

    protected $casts = [
        'is_admin'      => 'boolean',
        'email_sent'    => 'boolean',
        'email_sent_at' => 'datetime',
    ];

    public function contact()
    {
        return $this->belongsTo(ContactMessage::class, 'contact_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
