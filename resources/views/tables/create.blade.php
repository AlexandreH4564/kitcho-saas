<x-layouts::app :title="'Nova mesa'">
    <div class="mx-auto max-w-2xl">

        <h1 class="mb-6 text-2xl font-semibold">
            Nova mesa
        </h1>

        <form
            method="POST"
            action="{{ route('establishments.tables.store', $establishment) }}"
            class="flex flex-col gap-6"
        >
            @csrf

            <flux:input
                name="number"
                label="Número da mesa"
                type="number"
                min="1"
                :value="old('number')"
                required
                autofocus
                placeholder="Ex: 1"
            />

            <div class="flex gap-3">
                <flux:button
                    type="submit"
                    variant="primary"
                >
                    Criar mesa
                </flux:button>

                <flux:button
                    href="{{ route('establishments.tables.index', $establishment) }}"
                >
                    Cancelar
                </flux:button>
            </div>
        </form>

    </div>
</x-layouts::app>