<x-layouts::app :title="'Editar pedido #' . $order->id">

    <div class="flex flex-col gap-6">

        <div>
            <h1 class="text-2xl font-bold">
                Editar pedido #{{ $order->id }}
            </h1>

            <p class="text-sm text-zinc-500">
                {{ $establishment->name }}
            </p>
        </div>

        @if ($errors->any())
            <div class="rounded-lg bg-red-100 p-4 text-sm text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('establishments.orders.update', [
                'establishment' => $establishment,
                'order' => $order,
            ]) }}"
            class="flex flex-col gap-6"
        >
            @csrf
            @method('PUT')

            <flux:select
                name="status"
                label="Status"
                required
            >
                <option value="pending" @selected($order->status === 'pending')>
                    Pendente
                </option>

                <option value="preparing" @selected($order->status === 'preparing')>
                    Preparando
                </option>

                <option value="ready" @selected($order->status === 'ready')>
                    Pronto
                </option>

                <option value="completed" @selected($order->status === 'completed')>
                    Concluído
                </option>

                <option value="cancelled" @selected($order->status === 'cancelled')>
                    Cancelado
                </option>
            </flux:select>

            <div class="flex gap-3">

                <flux:button
                    type="submit"
                    variant="primary"
                >
                    Salvar alterações
                </flux:button>

                <flux:button
                    :href="route('establishments.orders.show', [
                        'establishment' => $establishment,
                        'order' => $order,
                    ])"
                    wire:navigate
                >
                    Cancelar
                </flux:button>

            </div>

        </form>

    </div>

</x-layouts::app>