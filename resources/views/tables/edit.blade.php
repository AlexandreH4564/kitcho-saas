<x-layouts::app :title="'Editar mesa'">
    <div class="mx-auto max-w-2xl">

        <h1 class="mb-6 text-2xl font-semibold">
            Editar mesa
        </h1>

        <form
            method="POST"
            action="{{ route('establishments.tables.update', [$establishment, $table]) }}"
            class="flex flex-col gap-6"
        >
            @csrf
            @method('PUT')

            <flux:input
                name="number"
                label="Número da mesa"
                type="number"
                min="1"
                :value="old('number', $table->number)"
                required
                autofocus
            />

            <div>
                <label
                    for="status"
                    class="mb-2 block text-sm font-medium"
                >
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    required
                    class="w-full rounded-lg border border-zinc-300 px-3 py-2"
                >
                    <option
                        value="available"
                        @selected(old('status', $table->status) === 'available')
                    >
                        Disponível
                    </option>

                    <option
                        value="occupied"
                        @selected(old('status', $table->status) === 'occupied')
                    >
                        Ocupada
                    </option>
                </select>

                @error('status')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex gap-3">
                <flux:button
                    type="submit"
                    variant="primary"
                >
                    Salvar alterações
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