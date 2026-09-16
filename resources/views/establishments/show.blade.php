<x-layouts::app :title="$establishment->name">

    <div class="p-6 max-w-3xl">

        <div class="flex items-center justify-between mb-6">

            <div>
                <h1 class="text-2xl font-bold">
                    {{ $establishment->name }}
                </h1>

                <p class="text-gray-600">
                    Detalhes do estabelecimento
                </p>
            </div>

            <flux:button
                :href="route('establishments.edit', $establishment)"
                wire:navigate
            >
                Editar
            </flux:button>

        </div>

        <div class="rounded-xl border p-6">

            <div class="mb-5">
                <p class="text-sm text-gray-500">
                    Nome
                </p>

                <p class="font-medium">
                    {{ $establishment->name }}
                </p>
            </div>

            <div class="mb-5">
                <p class="text-sm text-gray-500">
                    Endereço
                </p>

                <p class="font-medium">
                    {{ $establishment->address }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Slug
                </p>

                <p class="font-medium">
                    {{ $establishment->slug }}
                </p>
            </div>

        </div>

    </div>

</x-layouts::app>