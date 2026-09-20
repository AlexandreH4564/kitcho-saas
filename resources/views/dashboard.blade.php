<x-layouts::app :title="__('Dashboard')">

    <div class="flex flex-col gap-8">

        {{-- Cabeçalho --}}
        <div>
            <h1 class="ola">
                {{ __('Olá, :name!', ['name' => Auth::user()->name]) }}
            </h1>

            <flux:text class="mt-1">
                Visão geral do seu estabelecimento.
            </flux:text>
        </div>


        {{-- Estabelecimento --}}
@if ($establishment)

    <div>
        <flux:text class="text-sm">
            Estabelecimento
        </flux:text>

        <flux:heading size="lg" class="mt-1">
            {{ $establishment->name }}
        </flux:heading>
    </div>

@else

    <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">

        <flux:heading size="sm">
            Nenhum estabelecimento cadastrado
        </flux:heading>

        <flux:text class="mt-1">
            Cadastre seu estabelecimento para começar a utilizar o Kitcho.
        </flux:text>

        <flux:button
            class="mt-4"
            variant="primary"
            :href="route('establishments.create')"
            wire:navigate
        >
            Criar estabelecimento
        </flux:button>

    </div>

@endif


        {{-- Resumo --}}
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">

            {{-- Pedidos hoje --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">

                <flux:text class="text-sm">
                    Pedidos hoje
                </flux:text>

                <flux:heading size="xl" class="mt-2">
                    {{ $ordersToday }}
                </flux:heading>

                <flux:text class="mt-1 text-sm">
                    Pedidos realizados hoje
                </flux:text>

            </div>


            {{-- Faturamento --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">

                <flux:text class="text-sm">
                    Faturamento hoje
                </flux:text>

                <flux:heading size="xl" class="mt-2">
                    R$ {{ number_format($revenueToday, 2, ',', '.') }}
                </flux:heading>

                <flux:text class="mt-1 text-sm">
                    Total dos pedidos concluídos
                </flux:text>

            </div>


            {{-- Mesas ocupadas --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">

                <flux:text class="text-sm">
                    Mesas ocupadas
                </flux:text>

                <flux:heading size="xl" class="mt-2">
                    {{ $occupiedTables }}
                </flux:heading>

                <flux:text class="mt-1 text-sm">
                    Mesas em atendimento
                </flux:text>

            </div>


            {{-- Pedidos pendentes --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">

                <flux:text class="text-sm">
                    Pedidos pendentes
                </flux:text>

                <flux:heading size="xl" class="mt-2">
                    {{ $pendingOrders }}
                </flux:heading>

                <flux:text class="mt-1 text-sm">
                    Aguardando atendimento
                </flux:text>

            </div>

        </div>


        {{-- Conteúdo principal --}}
        <div class="grid gap-6 lg:grid-cols-3">


            {{-- Pedidos recentes --}}
            <div class="lg:col-span-2">

                <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">

                    <div class="flex items-center justify-between border-b border-zinc-200 p-5 dark:border-zinc-700">

                        <div>
                            <flux:heading size="lg">
                                Pedidos recentes
                            </flux:heading>

                            <flux:text class="mt-1">
                                Acompanhe os últimos pedidos realizados.
                            </flux:text>
                        </div>

                        @if ($establishment)

                            <flux:button
                                variant="ghost"
                                :href="route('establishments.orders.index', $establishment)"
                                wire:navigate
                            >
                                Ver todos
                            </flux:button>

                        @endif

                    </div>


                    <div class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse ($recentOrders as $order)

                            <div class="flex items-center justify-between gap-4 p-5">

                                <div class="flex items-center gap-4">

                                    <div class="flex size-10 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800">

                                        <flux:icon
                                            name="clipboard-document-list"
                                            class="size-5 text-zinc-500"
                                        />

                                    </div>

                                    <div>

                                        <flux:heading size="sm">
                                            Pedido #{{ $order->id }}
                                        </flux:heading>

                                        <flux:text class="mt-1 text-sm">
                                            Mesa {{ $order->table->number }}
                                            @if ($order->customer_name)
                                                · {{ $order->customer_name }}
                                            @endif
                                        </flux:text>

                                    </div>

                                </div>


                                <div class="text-right">

                                    <flux:heading size="sm">
                                        R$ {{ number_format($order->total, 2, ',', '.') }}
                                    </flux:heading>

                                    <flux:text class="mt-1 text-sm">
                                        @switch($order->status)

                                            @case('pending')
                                                Pendente
                                                @break

                                            @case('preparing')
                                                Preparando
                                                @break

                                            @case('ready')
                                                Pronto
                                                @break

                                            @case('completed')
                                                Concluído
                                                @break

                                            @case('cancelled')
                                                Cancelado
                                                @break

                                            @default
                                                {{ ucfirst($order->status) }}

                                        @endswitch
                                    </flux:text>

                                </div>

                            </div>

                        @empty

                            <div class="flex flex-col items-center justify-center py-12 text-center">

                                <flux:icon
                                    name="clipboard-document-list"
                                    class="mb-3 size-10 text-zinc-400"
                                />

                                <flux:heading size="sm">
                                    Nenhum pedido recente
                                </flux:heading>

                                <flux:text class="mt-1 max-w-sm">
                                    Quando seus clientes realizarem pedidos,
                                    eles aparecerão aqui.
                                </flux:text>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>


            {{-- Acesso rápido --}}
            <div>

                <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">

                    <div class="border-b border-zinc-200 p-5 dark:border-zinc-700">

                        <flux:heading size="lg">
                            Acesso rápido
                        </flux:heading>

                        <flux:text class="mt-1">
                            Ações frequentes.
                        </flux:text>

                    </div>


                    @if ($establishment)

                        <div class="flex flex-col gap-2 p-3">

                            <flux:button
                                variant="ghost"
                                class="justify-start"
                                icon="clipboard-document-list"
                                :href="route('establishments.orders.index', $establishment)"
                                wire:navigate
                            >
                                Pedidos
                            </flux:button>


                            <flux:button
                                variant="ghost"
                                class="justify-start"
                                icon="squares-2x2"
                                :href="route('establishments.products.index', $establishment)"
                                wire:navigate
                            >
                                Cardápio
                            </flux:button>


                            <flux:button
                                variant="ghost"
                                class="justify-start"
                                icon="table-cells"
                                :href="route('establishments.tables.index', $establishment)"
                                wire:navigate
                            >
                                Mesas
                            </flux:button>


                            <flux:button
                                variant="ghost"
                                class="justify-start"
                                icon="qr-code"
                                :href="route('establishments.qr-codes', $establishment)"
                                wire:navigate
                            >
                                QR Codes
                            </flux:button>

                        </div>

                    @else

                        <div class="p-5">

                            <flux:text>
                                Você ainda não possui um estabelecimento.
                            </flux:text>

                            <flux:button
                                class="mt-4"
                                :href="route('establishments.create')"
                                wire:navigate
                            >
                                Criar estabelecimento
                            </flux:button>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-layouts::app>
