<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        
        $establishment = Establishment::where('slug', $slug)
            ->firstOrFail();

        $table = null;

        if ($request->filled('table')) {
            $table = $establishment->tables()
                ->whereKey($request->integer('table'))
                ->where('status', 'available')
                ->first();

            if (!$table) {
                abort(404, 'Mesa não encontrada ou indisponível.');
            }
        }

        $categories = $establishment->categories()
            ->with([
                'products' => function ($query) {
                    $query->where('active', true)
                        ->orderBy('name');
                },
            ])
            ->orderBy('name')
            ->get();

        $products = $categories
            ->flatMap(function ($category) {
                return $category->products;
            })
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float) $product->price,
                ];
            })
            ->values();

        return view('menu.show', compact(
            'establishment',
            'categories',
            'products',
            'table'
        ));
    }

    public function checkout(
    Request $request,
    string $slug
): View {
    $establishment = Establishment::where('slug', $slug)
        ->firstOrFail();

    $selectedTable = null;

    if ($request->filled('table')) {
        $selectedTable = $establishment->tables()
            ->whereKey($request->integer('table'))
            ->where('status', 'available')
            ->first();

        if (!$selectedTable) {
            abort(404, 'Mesa não encontrada ou indisponível.');
        }
    }

    $tables = $establishment->tables()
        ->where('status', 'available')
        ->orderBy('number')
        ->get();

    $products = $establishment->products()
        ->where('active', true)
        ->get()
        ->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
            ];
        })
        ->values();

    return view('menu.checkout', compact(
        'establishment',
        'tables',
        'products',
        'selectedTable'
    ));
}

    public function storeOrder(
        Request $request,
        string $slug
    ): RedirectResponse {
        $establishment = Establishment::where('slug', $slug)
            ->firstOrFail();

        $validated = $request->validate([
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'table_id' => [
                'required',
                'integer',
            ],

            'cart' => [
                'required',
                'array',
            ],

            'cart.*.product_id' => [
                'required',
                'integer',
            ],

            'cart.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $table = $establishment->tables()
            ->whereKey($validated['table_id'])
            ->where('status', 'available')
            ->first();

        if (!$table) {
            return back()
                ->withErrors([
                    'table_id' => 'Esta mesa não está mais disponível.',
                ])
                ->withInput();
        }

        $cartItems = collect($validated['cart']);

        $productIds = $cartItems
            ->pluck('product_id')
            ->unique();

        $products = $establishment->products()
            ->whereIn('id', $productIds)
            ->where('active', true)
            ->get()
            ->keyBy('id');

        if ($products->count() !== $productIds->count()) {
            return back()
                ->withErrors([
                    'cart' => 'Um ou mais produtos não estão mais disponíveis.',
                ])
                ->withInput();
        }

            $order = DB::transaction(function () use (
                $establishment,
                $table,
                $cartItems,
                $products,
                $validated
            ) {
            $order = $establishment->orders()->create([
                'table_id' => $table->id,
                'customer_name' => $validated['customer_name'],
                'status' => 'pending',
                'total' => 0,
            ]);

            $total = 0;

            foreach ($cartItems as $item) {
                $product = $products->get($item['product_id']);

                $quantity = (int) $item['quantity'];

                $unitPrice = (float) $product->price;

                $subtotal = $unitPrice * $quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update([
                'total' => $total,
            ]);

            $table->update([
                'status' => 'occupied',
            ]);

            return $order;
        });

        return redirect()
            ->route('menu.order-success', [
                'slug' => $establishment->slug,
                'order' => $order,
            ]);
    }

    public function orderSuccess(
    string $slug,
    int $order
): View {
    $establishment = Establishment::where('slug', $slug)
        ->firstOrFail();

    $order = $establishment->orders()
        ->with([
            'table',
            'items.product',
        ])
        ->findOrFail($order);

    return view('menu.order-success', compact(
        'establishment',
        'order'
    ));
}
}