<x-layouts::app :title="'Novo pedido'">
    <div class="flex flex-col gap-6">

        <div>
            <h1 class="text-2xl font-bold">
                Novo pedido
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
            action="{{ route('establishments.orders.store', $establishment) }}"
            class="flex flex-col gap-6"
        >
            @csrf

            <div>
                <flux:select
                    name="table_id"
                    label="Mesa"
                    required
                >
                    <option value="">Selecione uma mesa</option>

                    @foreach ($tables as $table)
                        <option
                            value="{{ $table->id }}"
                            @selected(old('table_id') == $table->id)
                        >
                            Mesa {{ $table->number }}
                            @if ($table->status === 'occupied')
                                — Ocupada
                            @endif
                        </option>
                    @endforeach
                </flux:select>
            </div>

            <div>
                <h2 class="mb-4 text-lg font-semibold">
                    Produtos
                </h2>

                <div class="flex flex-col gap-4">

                    @forelse ($products as $product)
    <div class="rounded-lg border border-zinc-200 p-4">

        <div class="flex items-center justify-between gap-4">

            <div>
                <p class="font-medium">
                    {{ $product->name }}
                </p>

                <p class="text-sm text-zinc-500">
                    {{ $product->category->name }}
                </p>

                @if ($product->description)
                    <p class="mt-1 text-sm text-zinc-500">
                        {{ $product->description }}
                    </p>
                @endif

                <p class="mt-2 font-medium">
                    R$
                    {{ number_format($product->price, 2, ',', '.') }}
                </p>
            </div>

            <div class="w-24">

                <flux:input
                    type="number"
                    name="items[{{ $loop->index }}][quantity]"
                    min="0"
                    value="{{ old("items.{$loop->index}.quantity", 0) }}"
                    label="Quantidade"
                />

                <input
                    type="hidden"
                    name="items[{{ $loop->index }}][product_id]"
                    value="{{ $product->id }}"
                >

            </div>

        </div>

    </div>
@empty
                        <div class="rounded-lg border border-zinc-200 p-6">
                            <p class="text-zinc-500">
                                Nenhum produto ativo cadastrado.
                            </p>
                        </div>
                    @endforelse

                </div>
            </div>

            <div class="flex gap-3">
                <flux:button
                    type="submit"
                    variant="primary"
                >
                    Criar pedido
                </flux:button>

                <flux:button
                    :href="route('establishments.orders.index', $establishment)"
                    wire:navigate
                >
                    Cancelar
                </flux:button>
            </div>

        </form>

    </div>
</x-layouts::app>