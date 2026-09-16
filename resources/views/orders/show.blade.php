<x-layouts::app :title="'Pedido #' . $order->id">

    <div class="space-y-6">

        {{-- Cabeçalho --}}
        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-zinc-500">
                    Pedido #{{ $order->id }}
                </p>

                <h1 class="text-2xl font-bold">
                    {{ $order->customer_name ?? 'Cliente' }}
                </h1>
            </div>

            <a
                href="{{ route('establishments.orders.index', $order->establishment_id) }}"
                class="rounded-lg border px-4 py-2 text-sm font-medium hover:bg-zinc-50"
            >
                ← Voltar
            </a>

        </div>


        {{-- Informações do pedido --}}
        <div class="grid gap-4 md:grid-cols-3">

            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-zinc-500">
                    Mesa
                </p>

                <p class="mt-1 text-xl font-semibold">
                    {{ $order->table->number }}
                </p>
            </div>


            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-zinc-500">
                    Status
                </p>

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
                    class="mt-2 inline-block rounded-full px-3 py-1 text-sm font-medium {{ $statusClasses[$order->status] ?? 'bg-zinc-100 text-zinc-700' }}"
                >
                    {{ $statusLabels[$order->status] ?? $order->status }}
                </span>
            </div>


            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-zinc-500">
                    Total
                </p>

                <p class="mt-1 text-xl font-semibold">
                    R$ {{ number_format($order->total, 2, ',', '.') }}
                </p>
            </div>

        </div>


        {{-- Itens --}}
        <div class="rounded-xl border bg-white shadow-sm">

            <div class="border-b px-5 py-4">
                <h2 class="font-semibold">
                    Itens do pedido
                </h2>
            </div>

            <div class="divide-y">

                @foreach ($order->items as $item)

                    <div class="flex items-center justify-between px-5 py-4">

                        <div>
                            <p class="font-medium">
                                {{ $item->product->name }}
                            </p>

                            <p class="text-sm text-zinc-500">
                                {{ $item->quantity }} ×
                                R$ {{ number_format($item->unit_price, 2, ',', '.') }}
                            </p>
                        </div>

                        <p class="font-semibold">
                            R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                        </p>

                    </div>

                @endforeach

            </div>

        </div>


        {{-- Ações --}}
        @if ($order->status !== 'completed' && $order->status !== 'cancelled')

            <div class="rounded-xl border bg-white p-5 shadow-sm">

                <h2 class="font-semibold">
                    Ações do pedido
                </h2>

                <div class="mt-4 flex flex-wrap gap-3">

                    @if ($order->status === 'pending')

                        <form
                            method="POST"
                            action="{{ route('establishments.orders.update', [
                                'establishment' => $order->establishment_id,
                                'order' => $order->id,
                            ]) }}"
                        >
                            @csrf
                            @method('PUT')

                            <input
                                type="hidden"
                                name="status"
                                value="preparing"
                            >

                            <button
                                type="submit"
                                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                            >
                                Iniciar preparo
                            </button>
                        </form>

                    @elseif ($order->status === 'preparing')

                        <form
                            method="POST"
                            action="{{ route('establishments.orders.update', [
                                'establishment' => $order->establishment_id,
                                'order' => $order->id,
                            ]) }}"
                        >
                            @csrf
                            @method('PUT')

                            <input
                                type="hidden"
                                name="status"
                                value="ready"
                            >

                            <button
                                type="submit"
                                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
                            >
                                Marcar como pronto
                            </button>
                        </form>

                    @elseif ($order->status === 'ready')

                        <form
                            method="POST"
                            action="{{ route('establishments.orders.update', [
                                'establishment' => $order->establishment_id,
                                'order' => $order->id,
                            ]) }}"
                        >
                            @csrf
                            @method('PUT')

                            <input
                                type="hidden"
                                name="status"
                                value="completed"
                            >

                            <button
                                type="submit"
                                class="rounded-lg bg-black px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800"
                            >
                                Concluir pedido
                            </button>
                        </form>

                    @endif


                    {{-- Cancelar --}}
                    @if ($order->status === 'pending' || $order->status === 'preparing')

                        <form
                            method="POST"
                            action="{{ route('establishments.orders.update', [
                                'establishment' => $order->establishment_id,
                                'order' => $order->id,
                            ]) }}"
                        >
                            @csrf
                            @method('PUT')

                            <input
                                type="hidden"
                                name="status"
                                value="cancelled"
                            >

                            <button
                                type="submit"
                                class="rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                            >
                                Cancelar pedido
                            </button>
                        </form>

                    @endif

                </div>

            </div>

        @endif

    </div>

</x-layouts::app>