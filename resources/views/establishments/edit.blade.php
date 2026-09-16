<x-layouts::app :title="__('Editar estabelecimento')">

    <div class="p-6 max-w-2xl">

        <div class="mb-6">
            <h1 class="text-2xl font-bold">
                Editar estabelecimento
            </h1>

            <p class="text-gray-600">
                Atualize as informações do estabelecimento.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('establishments.update', $establishment) }}"
            class="flex flex-col gap-5"
        >
            @csrf
            @method('PUT')

            <flux:input
                name="name"
                label="Nome"
                :value="old('name', $establishment->name)"
                required
            />

            @error('name')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <flux:textarea
                name="address"
                label="Endereço"
                required
            >{{ old('address', $establishment->address) }}</flux:textarea>

            @error('address')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="flex gap-2">

                <flux:button
                    variant="primary"
                    type="submit"
                >
                    Salvar alterações
                </flux:button>

                <flux:button
                    :href="route('establishments.index')"
                    wire:navigate
                >
                    Cancelar
                </flux:button>

            </div>

        </form>

    </div>

</x-layouts::app>