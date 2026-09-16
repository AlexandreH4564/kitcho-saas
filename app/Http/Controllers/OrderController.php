<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Establishment $establishment): View
    {
        $this->authorizeEstablishment($establishment);

        $orders = $establishment->orders()
            ->with('table')
            ->latest()
            ->get();

        return view('orders.index', compact('establishment', 'orders'));
    }

    public function create(Establishment $establishment): View
    {
        $this->authorizeEstablishment($establishment);

        $tables = $establishment->tables()
            ->orderBy('number')
            ->get();

        $products = $establishment->products()
            ->where('active', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('orders.create', compact(
            'establishment',
            'tables',
            'products'
        ));
    }

    public function store(
        Request $request,
        Establishment $establishment
    ): RedirectResponse {
        $this->authorizeEstablishment($establishment);

        $validated = $request->validate([
            'table_id' => [
                'required',
                'integer',
                'exists:tables,id',
            ],

            'items' => [
                'required',
                'array',
            ],

            'items.*.product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'items.*.quantity' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $items = collect($validated['items'])
            ->filter(fn ($item) => (int) ($item['quantity'] ?? 0) > 0)
            ->values();

        if ($items->isEmpty()) {
            return back()
                ->withErrors([
                    'items' => 'Selecione pelo menos um produto para criar o pedido.',
                ])
                ->withInput();
        }

        $table = $establishment->tables()
            ->whereKey($validated['table_id'])
            ->firstOrFail();

        $productIds = $items
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
                    'items' => 'Um ou mais produtos selecionados são inválidos.',
                ])
                ->withInput();
        }

        $order = DB::transaction(function () use (
            $establishment,
            $table,
            $items,
            $products
        ) {
            $order = $establishment->orders()->create([
                'table_id' => $table->id,
                'status' => 'pending',
                'total' => 0,
            ]);

            $total = 0;

            foreach ($items as $item) {
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
            ->route('establishments.orders.show', [
                'establishment' => $establishment,
                'order' => $order,
            ])
            ->with('success', 'Pedido criado com sucesso.');
    }

    public function show(
        Establishment $establishment,
        Order $order
    ): View {
        $this->authorizeEstablishment($establishment);
        $this->authorizeOrder($establishment, $order);

        $order->load([
            'table',
            'items.product',
        ]);

        return view('orders.show', compact('establishment', 'order'));
    }

    public function edit(
        Establishment $establishment,
        Order $order
    ): View {
        $this->authorizeEstablishment($establishment);
        $this->authorizeOrder($establishment, $order);

        return view('orders.edit', compact(
            'establishment',
            'order'
        ));
    }

    public function update(
    Request $request,
    Establishment $establishment,
    Order $order
): RedirectResponse {
    $this->authorizeEstablishment($establishment);
    $this->authorizeOrder($establishment, $order);

    $validated = $request->validate([
        'status' => [
            'required',
            'in:pending,preparing,ready,completed,cancelled',
        ],
    ]);

    $oldStatus = $order->status;
    $newStatus = $validated['status'];

    if (!$this->canChangeStatus($oldStatus, $newStatus)) {
        return back()
            ->withErrors([
                'status' => 'Não é possível alterar o pedido de '
                    . $this->statusLabel($oldStatus)
                    . ' para '
                    . $this->statusLabel($newStatus)
                    . '.',
            ])
            ->withInput();
    }

    $order->update([
        'status' => $newStatus,
    ]);

    if (
        in_array($newStatus, ['completed', 'cancelled'])
    ) {
        $order->table()->update([
            'status' => 'available',
        ]);
    }

    return redirect()
        ->route('establishments.orders.show', [
            'establishment' => $establishment,
            'order' => $order,
        ])
        ->with('success', 'Pedido atualizado com sucesso.');
}

    public function destroy(
        Establishment $establishment,
        Order $order
    ): RedirectResponse {
        $this->authorizeEstablishment($establishment);
        $this->authorizeOrder($establishment, $order);

        $table = $order->table;

        $order->delete();

        if ($table) {
            $table->update([
                'status' => 'available',
            ]);
        }

        return redirect()
            ->route('establishments.orders.index', $establishment)
            ->with('success', 'Pedido excluído com sucesso.');
    }

    private function canChangeStatus(
    string $currentStatus,
    string $newStatus
): bool {
    $allowedTransitions = [
        'pending' => [
            'preparing',
            'cancelled',
        ],

        'preparing' => [
            'ready',
            'cancelled',
        ],

        'ready' => [
            'completed',
            'cancelled',
        ],

        'completed' => [],

        'cancelled' => [],
    ];

    return in_array(
        $newStatus,
        $allowedTransitions[$currentStatus] ?? [],
        true
    );
}

private function statusLabel(string $status): string
{
    return match ($status) {
        'pending' => 'Pendente',
        'preparing' => 'Preparando',
        'ready' => 'Pronto',
        'completed' => 'Concluído',
        'cancelled' => 'Cancelado',
        default => $status,
    };
}


    private function authorizeEstablishment(
        Establishment $establishment
    ): void {
        abort_unless(
            auth()->user()
                ->establishments()
                ->whereKey($establishment->id)
                ->exists(),
            403
        );
    }

    private function authorizeOrder(
        Establishment $establishment,
        Order $order
    ): void {
        abort_unless(
            $order->establishment_id === $establishment->id,
            404
        );
    }
}