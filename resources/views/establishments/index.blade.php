<x-layouts::app :title="__('Estabelecimento')">

    <div class="p-6">

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($establishment)

            <div class="mb-6">
                <h1 class="text-2xl font-bold">
                    Meu estabelecimento
                </h1>

                <p class="text-gray-600">
                    Gerencie as informações do seu estabelecimento.
                </p>
            </div>

            <div class="max-w-2xl rounded-xl border p-6">

                <h2 class="text-xl font-semibold">
                    {{ $establishment->name }}
                </h2>

                <p class="mt-2 text-gray-600">
                    {{ $establishment->address }}
                </p>

                <p class="mt-4 text-sm text-gray-500">
                    /{{ $establishment->slug }}
                </p>

                <div class="mt-6 flex gap-2">

                    <flux:button
                        variant="primary"
                        :href="route('establishments.show', $establishment)"
                        wire:navigate
                    >
                        Ver estabelecimento
                    </flux:button>

                    <flux:button
                        :href="route('establishments.edit', $establishment)"
                        wire:navigate
                    >
                        Editar
                    </flux:button>

                </div>

            </div>

        @else

            <div class="max-w-2xl">

                <h1 class="text-2xl font-bold">
                    Meu estabelecimento
                </h1>

                <p class="mt-2 text-gray-600">
                    Você ainda não possui um estabelecimento.
                </p>

                <div class="mt-6">

                    <flux:button
                        variant="primary"
                        :href="route('establishments.create')"
                        wire:navigate
                    >
                        + Criar estabelecimento
                    </flux:button>

                </div>

            </div>

        @endif

    </div>

</x-layouts::app>