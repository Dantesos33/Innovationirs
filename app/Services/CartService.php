<?php
namespace App\Services;

use App\Models\HeavyDutyTool;
use Illuminate\Support\Collection;

class CartService
{
    protected const SESSION_KEY = 'hdt_cart';

    // ─── Read ──────────────────────────────────────────────────────────

    /** Return full cart array from session. */
    public function all(): array
    {
        return session(self::SESSION_KEY, []);
    }

    /** Return cart as a Collection of enriched items. */
    public function items(): Collection
    {
        $cart = $this->all();
        if (empty($cart)) {
            return collect();
        }

        // Eager-load tools so we get fresh prices/names
        $tools = HeavyDutyTool::active()
            ->whereIn('id', array_keys($cart))
            ->with('primaryImage')
            ->get()
            ->keyBy('id');

        $items = collect();
        foreach ($cart as $toolId => $row) {
            $tool = $tools->get($toolId);
            if (! $tool) {
                continue; // tool deleted or deactivated — skip silently
            }

            $qty   = (int) $row['quantity'];
            $price = (float) $tool->effective_price;
            $items->push([
                'tool_id'      => $tool->id,
                'tool'         => $tool,
                'name'         => $tool->name,
                'sku'          => $tool->sku,
                'part_number'  => $tool->part_number,
                'price'        => $price,
                'quantity'     => $qty,
                'line_total'   => round($price * $qty, 2),
                'image_url'    => $tool->primaryImage?->public_url ?? $tool->image_url,
                'url'          => route('tools.show', $tool->slug),
                'stock_status' => $tool->stock_status,
                'max_qty'      => $tool->stock_quantity ?: 99,
            ]);
        }

        return $items;
    }

    /** Total item count (sum of quantities). */
    public function count(): int
    {
        return (int) array_sum(array_column($this->all(), 'quantity'));
    }

    /** Cart subtotal. */
    public function subtotal(): float
    {
        return round($this->items()->sum('line_total'), 2);
    }

    /** Estimated tax (8% of subtotal — placeholder; real tax in Chunk 4). */
    public function tax(): float
    {
        return round($this->subtotal() * 0.08, 2);
    }

    /** Flat shipping: free over $200, otherwise $15. */
    public function shipping(): float
    {
        return $this->subtotal() >= 200 ? 0.0 : 15.0;
    }

    /** Grand total. */
    public function total(): float
    {
        return round($this->subtotal() + $this->tax() + $this->shipping(), 2);
    }

    /** True if cart is empty. */
    public function isEmpty(): bool
    {
        return empty($this->all());
    }

    // ─── Write ─────────────────────────────────────────────────────────

    /**
     * Add a tool to the cart (or increment quantity).
     * Returns ['success', 'message', 'cart_count'].
     */
    public function add(int $toolId, int $qty = 1): array
    {
        $tool = HeavyDutyTool::active()->find($toolId);

        if (! $tool) {
            return ['success' => false, 'message' => 'Tool not found or unavailable.'];
        }
        if ($tool->stock_status === 'out_of_stock') {
            return ['success' => false, 'message' => 'This item is currently out of stock.'];
        }

        $qty  = max(1, min($qty, $tool->stock_quantity ?: 99));
        $cart = $this->all();

        if (isset($cart[$toolId])) {
            $newQty                    = min($cart[$toolId]['quantity'] + $qty, $tool->stock_quantity ?: 99);
            $cart[$toolId]['quantity'] = $newQty;
        } else {
            $cart[$toolId] = ['quantity' => $qty];
        }

        session([self::SESSION_KEY => $cart]);

        return [
            'success' => true,
            'message' => "\"{$tool->name}\" added to cart.",
            'cart_count' => $this->count(),
        ];
    }

    /**
     * Set a specific quantity. Removes item if qty <= 0.
     */
    public function update(int $toolId, int $qty): void
    {
        $cart = $this->all();

        if ($qty <= 0) {
            unset($cart[$toolId]);
        } else {
            $tool          = HeavyDutyTool::active()->find($toolId);
            $maxQty        = $tool?->stock_quantity ?: 99;
            $cart[$toolId] = ['quantity' => min($qty, $maxQty)];
        }

        session([self::SESSION_KEY => $cart]);
    }

    /**
     * Remove a single item.
     */
    public function remove(int $toolId): void
    {
        $cart = $this->all();
        unset($cart[$toolId]);
        session([self::SESSION_KEY => $cart]);
    }

    /**
     * Empty the cart entirely.
     */
    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Return a plain array snapshot suitable for storing on a ToolOrder.
     */
    public function snapshot(): array
    {
        return $this->items()->map(fn($item) => [
            'tool_id'     => $item['tool_id'],
            'name'        => $item['name'],
            'sku'         => $item['sku'],
            'part_number' => $item['part_number'],
            'price'       => $item['price'],
            'quantity'    => $item['quantity'],
            'line_total'  => $item['line_total'],
        ])->values()->toArray();
    }
}
