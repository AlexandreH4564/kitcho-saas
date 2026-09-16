<x-layouts::app :title="__('Estabelecimentos')">

    <div class="p-6">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold">
                    Meus estabelecimentos
                </h1>

                <p class="text-gray-600">
                    Gerencie os estabelecimentos que você administra.
                </p>
            </div>

            <flux:button
                variant="primary"
                :href="route('establishments.create')"
                wire:navigate
            >
                + Novo estabelecimento
            </flux:button>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">

            @forelse ($establishments as $establishment)

                <div class="rounded-xl border p-5">

                    <h2 class="text-lg font-semibold">
                        {{ $establishment->name }}
                    </h2>

                    <p class="mt-2 text-sm text-gray-600">
                        {{ $establishment->address }}
                    </p>

                    <p class="mt-4 text-xs text-gray-500">
                        /{{ $establishment->slug }}
                    </p>

                    <div class="mt-5 flex gap-2">

                        <flux:button
                            size="sm"
                            :href="route('establishments.show', $establishment)"
                            wire:navigate
                        >
                            Ver
                        </flux:button>

                        <flux:button
                            size="sm"
                            :href="route('establishments.edit', $establishment)"
                            wire:navigate
                        >
                            Editar
                        </flux:button>

                        <form
                            method="POST"
                            action="{{ route('establishments.destroy', $establishment) }}"
                            onsubmit="return confirm('Tem certeza que deseja excluir este estabelecimento?')"
                        >
                            @csrf
                            @method('DELETE')

                            <flux:button
                                size="sm"
                                variant="danger"
                                type="submit"
                            >
                                Excluir
                            </flux:button>
                        </form>

                    </div>

                </div>

            @empty

                <div class="col-span-full rounded-xl border p-8 text-center">

                    <p class="text-gray-600">
                        Você ainda não possui nenhum estabelecimento.
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        Crie seu primeiro estabelecimento para começar.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</x-layouts::app>