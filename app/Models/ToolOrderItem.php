<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolOrderItem extends Model
{
    protected $table = 'tool_order_items';

    protected $fillable = [
        'order_id', 'tool_id',
        'tool_name', 'tool_sku', 'tool_part_number',
        'unit_price', 'quantity', 'line_total',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'quantity'   => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(ToolOrder::class, 'order_id');
    }

    public function tool()
    {
        return $this->belongsTo(HeavyDutyTool::class, 'tool_id');
    }
}
