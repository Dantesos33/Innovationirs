<?php
namespace App\Http\Controllers;

use App\Models\HeavyDutyTool;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cart
    ) {}

    // ─── Cart Page ─────────────────────────────────────────────────────

    public function index(): View
    {
        $items    = $this->cart->items();
        $subtotal = $this->cart->subtotal();
        $shipping = $this->cart->shipping();
        $tax      = $this->cart->tax();
        $total    = $this->cart->total();

        // Upsell: tools not already in cart
        $inCartIds    = $items->pluck('tool_id')->toArray();
        $relatedTools = HeavyDutyTool::active()
            ->whereNotIn('id', $inCartIds)
            ->with(['primaryImage', 'images'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('cart.index', compact(
            'items', 'subtotal', 'shipping', 'tax', 'total', 'relatedTools'
        ));
    }

    // ─── Add to Cart (AJAX) ────────────────────────────────────────────

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'tool_id'  => 'required|integer|exists:heavy_duty_tools,id',
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);

        $result = $this->cart->add(
            (int) $request->tool_id,
            (int) ($request->quantity ?? 1)
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    // ─── Update Quantity (AJAX or form) ────────────────────────────────

    public function update(Request $request): JsonResponse | RedirectResponse
    {
        $request->validate([
            'tool_id'  => 'required|integer',
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $this->cart->update(
            (int) $request->tool_id,
            (int) $request->quantity
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'cart_count' => $this->cart->count(),
                'subtotal'   => $this->cart->subtotal(),
                'shipping'   => $this->cart->shipping(),
                'tax'        => $this->cart->tax(),
                'total'      => $this->cart->total(),
            ]);
        }

        return back()->with('success', 'Cart updated.');
    }

    // ─── Remove Item (AJAX or form) ────────────────────────────────────

    public function remove(Request $request): JsonResponse | RedirectResponse
    {
        $request->validate(['tool_id' => 'required|integer']);

        $this->cart->remove((int) $request->tool_id);

        if ($request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'cart_count' => $this->cart->count(),
                'subtotal'   => $this->cart->subtotal(),
                'shipping'   => $this->cart->shipping(),
                'tax'        => $this->cart->tax(),
                'total'      => $this->cart->total(),
            ]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    // ─── Clear Cart ────────────────────────────────────────────────────

    public function clear(): RedirectResponse
    {
        $this->cart->clear();
        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }

    // ─── Mini-cart summary (AJAX) ──────────────────────────────────────

    public function summary(): JsonResponse
    {
        return response()->json([
            'count'    => $this->cart->count(),
            'subtotal' => number_format($this->cart->subtotal(), 2),
            'items'    => $this->cart->items()->map(fn($item) => [
                'tool_id'    => $item['tool_id'],
                'name'       => $item['name'],
                'price'      => number_format($item['price'], 2),
                'quantity'   => $item['quantity'],
                'line_total' => number_format($item['line_total'], 2),
                'image_url'  => $item['image_url'],
                'url'        => $item['url'],
            ])->values(),
        ]);
    }
}
