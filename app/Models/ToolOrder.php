<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolOrder extends Model
{
    protected $table = 'tool_orders';

    protected $fillable = [
        'order_number',
        'first_name', 'last_name', 'email', 'phone', 'company',
        'shipping_address', 'shipping_city', 'shipping_state',
        'shipping_zip', 'shipping_country',
        'subtotal', 'shipping_cost', 'tax', 'total',
        'stripe_payment_intent_id', 'stripe_charge_id',
        'payment_status', 'fulfillment_status',
        'tracking_number', 'notes', 'admin_notes',
        'ip_address', 'cart_snapshot',
    ];

    protected $casts = [
        'subtotal'      => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax'           => 'decimal:2',
        'total'         => 'decimal:2',
        'cart_snapshot' => 'array',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function items()
    {
        return $this->hasMany(ToolOrderItem::class, 'order_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    // ─── Accessors ────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getPaymentStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'     => 'badge-success',
            'failed'   => 'badge-danger',
            'refunded' => 'badge-secondary',
            default    => 'badge-new',
        };
    }

    public function getFulfillmentStatusBadgeAttribute(): string
    {
        return match ($this->fulfillment_status) {
            'processing' => 'badge-open',
            'shipped'    => 'badge-in-progress',
            'delivered'  => 'badge-success',
            'cancelled'  => 'badge-closed',
            default      => 'badge-new',
        };
    }

    // ─── Static helpers ───────────────────────────────────────────────

    public static function generateOrderNumber(): string
    {
        do {
            $number = 'HDT-' . strtoupper(substr(uniqid(), -6)) . '-' . rand(100, 999);
        } while (static::where('order_number', $number)->exists());

        return $number;
    }
}
