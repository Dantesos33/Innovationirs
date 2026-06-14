<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ToolOrder;
use Illuminate\Http\Request;

class AdminToolOrdersController extends Controller
{
    // ─── Index ────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = ToolOrder::with('items')->latest();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('first_name', 'like', "%{$term}%")
                  ->orWhere('last_name', 'like', "%{$term}%");
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('fulfillment_status')) {
            $query->where('fulfillment_status', $request->fulfillment_status);
        }

        $orders = $query->paginate(25)->withQueryString();

        return view('admin.tool-orders.index', compact('orders'));
    }

    // ─── Show ─────────────────────────────────────────────────────────

    public function show(ToolOrder $toolOrder)
    {
        $toolOrder->load('items.tool');
        return view('admin.tool-orders.show', ['order' => $toolOrder]);
    }

    // ─── Update Fulfillment Status ────────────────────────────────────

    public function updateStatus(Request $request, ToolOrder $toolOrder)
    {
        $request->validate([
            'fulfillment_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number'    => 'nullable|string|max:255',
            'admin_notes'        => 'nullable|string|max:2000',
        ]);

        $toolOrder->update($request->only([
            'fulfillment_status',
            'tracking_number',
            'admin_notes',
        ]));

        return back()->with('success', 'Order status updated.');
    }

    // ─── Export ───────────────────────────────────────────────────────

    public function export()
    {
        $orders = ToolOrder::with('items')->latest()->lazy(200);

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Order #', 'Date', 'Customer', 'Email',
                'Total', 'Payment Status', 'Fulfillment Status', 'Items',
            ]);
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i'),
                    $order->full_name,
                    $order->email,
                    number_format($order->total, 2),
                    $order->payment_status,
                    $order->fulfillment_status,
                    $order->items->count(),
                ]);
            }
            fclose($handle);
        }, 'tool-orders-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
