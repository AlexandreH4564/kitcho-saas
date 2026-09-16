<x-layouts::app :title="__('Novo estabelecimento')">

    <div class="p-6 max-w-2xl">

        <div class="mb-6">
            <h1 class="text-2xl font-bold">
                Novo estabelecimento
            </h1>

            <p class="text-gray-600">
                Cadastre um estabelecimento para começar a gerenciá-lo.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('establishments.store') }}"
            class="flex flex-col gap-5"
        >
            @csrf

            <flux:input
                name="name"
                label="Nome"
                :value="old('name')"
                placeholder="Ex: Pizzaria do João"
                required
            />

            @error('name')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <flux:textarea
                name="address"
                label="Endereço"
                placeholder="Rua, número, bairro, cidade..."
                required
            >{{ old('address') }}</flux:textarea>

            @error('address')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="flex gap-2">

                <flux:button
                    variant="primary"
                    type="submit"
                >
                    Criar estabelecimento
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