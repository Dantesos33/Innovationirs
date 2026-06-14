<?php
namespace App\Http\Controllers;

use App\Mail\ToolOrderAdminNotificationMail;
use App\Mail\ToolOrderConfirmationMail;
use App\Models\ToolOrder;
use App\Models\ToolOrderItem;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart
    ) {}

    // ─── Checkout page ────────────────────────────────────────────────

    public function index(): View | RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('info', 'Your cart is empty. Add some tools before checking out.');
        }

        $items    = $this->cart->items();
        $subtotal = $this->cart->subtotal();
        $shipping = $this->cart->shipping();
        $tax      = $this->cart->tax();
        $total    = $this->cart->total();

        $stripeKey = config('services.stripe.key');

        return view('checkout.index', compact(
            'items', 'subtotal', 'shipping', 'tax', 'total', 'stripeKey'
        ));
    }

    // ─── Create Stripe PaymentIntent (AJAX) ───────────────────────────

    public function createPaymentIntent(Request $request): JsonResponse
    {
        if ($this->cart->isEmpty()) {
            return response()->json(['error' => 'Cart is empty.'], 422);
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $totalCents = (int) round($this->cart->total() * 100);

            $intent = \Stripe\PaymentIntent::create([
                'amount'                    => $totalCents,
                'currency'                  => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata'                  => [
                    'source'       => 'heavy_duty_tools',
                    'cart_items'   => $this->cart->count(),
                    'subtotal_usd' => $this->cart->subtotal(),
                ],
            ]);

            // Store intent ID in session so we can match it on webhook / confirm
            session(['pending_payment_intent' => $intent->id]);

            return response()->json([
                'client_secret' => $intent->client_secret,
                'intent_id'     => $intent->id,
            ]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe PaymentIntent creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment service unavailable. Please try again.'], 500);
        }
    }

    // ─── Place Order (called after Stripe confirms payment) ──────────

    public function placeOrder(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'email'             => 'required|email|max:255',
            'phone'             => 'nullable|string|max:30',
            'company'           => 'nullable|string|max:150',
            'shipping_address'  => 'required|string|max:255',
            'shipping_city'     => 'required|string|max:100',
            'shipping_state'    => 'required|string|max:100',
            'shipping_zip'      => 'required|string|max:20',
            'shipping_country'  => 'required|string|max:2',
        ]);

        if ($this->cart->isEmpty()) {
            return response()->json(['error' => 'Cart is empty.'], 422);
        }

        try {
            // Verify payment with Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $intent = \Stripe\PaymentIntent::retrieve($request->payment_intent_id);

            if ($intent->status !== 'succeeded') {
                return response()->json([
                    'error' => 'Payment not confirmed. Status: ' . $intent->status,
                ], 422);
            }

            // Build order
            $items    = $this->cart->items();
            $subtotal = $this->cart->subtotal();
            $shipping = $this->cart->shipping();
            $tax      = $this->cart->tax();
            $total    = $this->cart->total();

            $order = ToolOrder::create([
                'order_number'             => ToolOrder::generateOrderNumber(),
                'first_name'               => $request->first_name,
                'last_name'                => $request->last_name,
                'email'                    => $request->email,
                'phone'                    => $request->phone,
                'company'                  => $request->company,
                'shipping_address'         => $request->shipping_address,
                'shipping_city'            => $request->shipping_city,
                'shipping_state'           => $request->shipping_state,
                'shipping_zip'             => $request->shipping_zip,
                'shipping_country'         => $request->shipping_country,
                'subtotal'                 => $subtotal,
                'shipping_cost'            => $shipping,
                'tax'                      => $tax,
                'total'                    => $total,
                'stripe_payment_intent_id' => $intent->id,
                'stripe_charge_id'         => $intent->latest_charge ?? null,
                'payment_status'           => 'paid',
                'fulfillment_status'       => 'pending',
                'ip_address'               => $request->ip(),
                'cart_snapshot'            => $this->cart->snapshot(),
            ]);

            // Create order items
            foreach ($items as $item) {
                ToolOrderItem::create([
                    'order_id'         => $order->id,
                    'tool_id'          => $item['tool_id'],
                    'tool_name'        => $item['name'],
                    'tool_sku'         => $item['sku'],
                    'tool_part_number' => $item['part_number'],
                    'unit_price'       => $item['price'],
                    'quantity'         => $item['quantity'],
                    'line_total'       => $item['line_total'],
                ]);
            }

            // Send emails
            try {
                Mail::to($order->email)->send(new ToolOrderConfirmationMail($order));
            } catch (\Exception $e) {
                Log::error('Order confirmation email failed: ' . $e->getMessage());
            }

            try {
                $adminEmail = config('amsparts.admin_email');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new ToolOrderAdminNotificationMail($order));
                }
            } catch (\Exception $e) {
                Log::error('Order admin notification email failed: ' . $e->getMessage());
            }

            // Clear cart
            $this->cart->clear();
            session()->forget('pending_payment_intent');

            // Store order number for confirmation page
            session(['last_order_number' => $order->order_number]);

            return response()->json([
                'success'      => true,
                'order_number' => $order->order_number,
                'redirect'     => route('checkout.confirmation'),
            ]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe order placement failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment verification failed. Please contact support.'], 500);
        } catch (\Exception $e) {
            Log::error('Order placement failed: ' . $e->getMessage());
            return response()->json(['error' => 'Order could not be placed. Please try again.'], 500);
        }
    }

    // ─── Order Confirmation page ──────────────────────────────────────

    public function confirmation(Request $request): View | RedirectResponse
    {
        $orderNumber = session('last_order_number');

        if (! $orderNumber) {
            return redirect()->route('tools.index');
        }

        $order = ToolOrder::with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('checkout.confirmation', compact('order'));
    }
}
