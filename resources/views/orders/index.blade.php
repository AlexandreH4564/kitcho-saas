<x-layouts::app :title="'Pedidos'">
    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">
                    Pedidos
                </h1>

                <p class="text-sm text-zinc-500">
                    Acompanhe os pedidos do estabelecimento.
                </p>
            </div>

            <a
                href="{{ route('establishments.orders.create', 1) }}"
                class="rounded-lg bg-black px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800"
            >
                + Novo pedido
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">

            @forelse ($orders as $order)

                <div class="rounded-xl border bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between gap-4">

                        <div>
                            <p class="text-sm text-zinc-500">
                                Pedido #{{ $order->id }}
                            </p>

                            <h2 class="mt-1 text-lg font-semibold">
                                {{ $order->customer_name ?? 'Cliente' }}
                            </h2>
                        </div>

                        @php
                            $statusLabels = [
                                'pending' => 'Pendente',
                                'preparing' => 'Em preparo',
                                'ready' => 'Pronto',
                                'completed' => 'Concluído',
                                'cancelled' => 'Cancelado',
                            ];

                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'preparing' => 'bg-blue-100 text-blue-800',
                                'ready' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-zinc-100 text-zinc-700',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                        @endphp

                        <span
                            class="rounded-full px-3 py-1 text-xs font-medium {{ $statusClasses[$order->status] ?? 'bg-zinc-100 text-zinc-700' }}"
                        >
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>

                    </div>

                    <div class="mt-5 space-y-2 text-sm">

                        <div class="flex justify-between">
                            <span class="text-zinc-500">
                                Mesa
                            </span>

                            <span class="font-medium">
                                {{ $order->table->number }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-zinc-500">
                                Itens
                            </span>

                            <span class="font-medium">
                                {{ $order->items->sum('quantity') }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-zinc-500">
                                Total
                            </span>

                            <span class="font-semibold">
                                R$ {{ number_format($order->total, 2, ',', '.') }}
                            </span>
                        </div>

                    </div>

                    <div class="mt-5 border-t pt-4">

                        <a
                            href="{{ route('establishments.orders.show', [
                                'establishment' => $order->establishment_id,
                                'order' => $order->id,
                            ]) }}"
                            class="block w-full rounded-lg border px-4 py-2 text-center text-sm font-medium hover:bg-zinc-50"
                        >
                            Ver pedido
                        </a>

                    </div>

                </div>

            @empty

                <div class="col-span-full rounded-xl border border-dashed p-10 text-center">

                    <p class="text-lg font-medium">
                        Nenhum pedido encontrado.
                    </p>

                    <p class="mt-1 text-sm text-zinc-500">
                        Quando um cliente fizer um pedido, ele aparecerá aqui.
                    </p>

                </div>

            @endforelse

        </div>

    </div>
</x-layouts::app>