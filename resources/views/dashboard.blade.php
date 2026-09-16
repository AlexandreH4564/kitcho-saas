<x-layouts::app :title="__('Dashboard')">

    <div class="flex flex-col gap-6">

        <div>
            <h1 class="text-2xl font-bold">
                Dashboard
            </h1>

            <p class="text-sm text-zinc-500">
                Acesso rápido ao sistema
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">

            {{-- Estabelecimentos --}}
            <a
                href="{{ route('establishments.index') }}"
                wire:navigate
                class="rounded-xl border border-neutral-200 p-6 transition hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800"
            >
                <h2 class="text-lg font-semibold">
                    Estabelecimentos
                </h2>

                <p class="mt-2 text-sm text-neutral-500">
                    Gerenciar estabelecimentos
                </p>
            </a>

            {{-- Categorias --}}
            <a
                href="{{ route('establishments.categories.index', 1) }}"
                wire:navigate
                class="rounded-xl border border-neutral-200 p-6 transition hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800"
            >
                <h2 class="text-lg font-semibold">
                    Categorias
                </h2>

                <p class="mt-2 text-sm text-neutral-500">
                    Gerenciar categorias do cardápio
                </p>
            </a>

            {{-- Produtos --}}
            <a
                href="{{ route('establishments.products.index', 1) }}"
                wire:navigate
                class="rounded-xl border border-neutral-200 p-6 transition hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800"
            >
                <h2 class="text-lg font-semibold">
                    Produtos
                </h2>

                <p class="mt-2 text-sm text-neutral-500">
                    Gerenciar produtos
                </p>
            </a>

            {{-- Mesas --}}
            <a
                href="{{ route('establishments.tables.index', 1) }}"
                wire:navigate
                class="rounded-xl border border-neutral-200 p-6 transition hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800"
            >
                <h2 class="text-lg font-semibold">
                    Mesas
                </h2>

                <p class="mt-2 text-sm text-neutral-500">
                    Gerenciar mesas
                </p>
            </a>

            {{-- Pedidos --}}
            <a
                href="{{ route('establishments.orders.index', 1) }}"
                wire:navigate
                class="rounded-xl border border-neutral-200 p-6 transition hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800"
            >
                <h2 class="text-lg font-semibold">
                    Pedidos
                </h2>

                <p class="mt-2 text-sm text-neutral-500">
                    Gerenciar pedidos
                </p>
            </a>

        </div>

    </div>

</x-layouts::app>