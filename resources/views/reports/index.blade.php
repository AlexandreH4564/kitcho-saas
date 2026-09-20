<x-layouts::app :title="__('Relatórios')">

    <div class="flex flex-col gap-8">

        {{-- Cabeçalho --}}
        <div>
            <flux:heading size="xl">
                Relatórios
            </flux:heading>

            <flux:text class="mt-1">
                Acompanhe o desempenho do seu estabelecimento.
            </flux:text>

            <flux:text class="mt-2 text-sm">
                {{ $establishment->name }}
            </flux:text>
        </div>


        {{-- Indicadores --}}
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm">
                    Faturamento
                </flux:text>

                <flux:heading size="xl" class="mt-2">
                    R$ {{ number_format($totalRevenue, 2, ',', '.') }}
                </flux:heading>

                <flux:text class="mt-1 text-sm">
                    Últimos 6 meses
                </flux:text>
            </div>


            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm">
                    Pedidos
                </flux:text>

                <flux:heading size="xl" class="mt-2">
                    {{ number_format($totalOrders, 0, ',', '.') }}
                </flux:heading>

                <flux:text class="mt-1 text-sm">
                    Pedidos concluídos
                </flux:text>
            </div>


            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm">
                    Ticket médio
                </flux:text>

                <flux:heading size="xl" class="mt-2">
                    R$ {{ number_format($averageTicket, 2, ',', '.') }}
                </flux:heading>

                <flux:text class="mt-1 text-sm">
                    Valor médio por pedido
                </flux:text>
            </div>


            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm">
                    Produtos analisados
                </flux:text>

                <flux:heading size="xl" class="mt-2">
                    {{ $topProducts->count() }}
                </flux:heading>

                <flux:text class="mt-1 text-sm">
                    Produtos com vendas
                </flux:text>
            </div>

        </div>


        {{-- Faturamento --}}
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">

            <div class="border-b border-zinc-200 p-5 dark:border-zinc-700">

                <flux:heading size="lg">
                    Faturamento por mês
                </flux:heading>

                <flux:text class="mt-1">
                    Evolução do faturamento nos últimos seis meses.
                </flux:text>

            </div>

            <div class="p-5">

                @if ($revenueByMonth->isEmpty())

                    <div class="py-12 text-center">

                        <flux:icon
                            name="chart-bar"
                            class="mx-auto mb-3 size-10 text-zinc-400"
                        />

                        <flux:heading size="sm">
                            Ainda não há dados suficientes
                        </flux:heading>

                        <flux:text class="mt-1">
                            Os dados aparecerão aqui conforme os pedidos forem concluídos.
                        </flux:text>

                    </div>

                @else

                    <div class="flex flex-col gap-4">

                        @foreach ($revenueByMonth as $month)

                            <div>

                                <div class="mb-1 flex items-center justify-between">

                                    <flux:text class="text-sm">
                                        {{ \Carbon\Carbon::parse($month->month)->translatedFormat('F/Y') }}
                                    </flux:text>

                                    <flux:text class="text-sm font-medium">
                                        R$ {{ number_format($month->total, 2, ',', '.') }}
                                    </flux:text>

                                </div>

                                <div class="h-3 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">

                                    @php
                                        $maxRevenue = $revenueByMonth->max('total') ?: 1;
                                        $percentage = ($month->total / $maxRevenue) * 100;
                                    @endphp

                                    <div
                                        class="h-full rounded-full bg-zinc-900 dark:bg-zinc-100"
                                        style="width: {{ $percentage }}%"
                                    ></div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>


        {{-- Produtos e categorias --}}
        <div class="grid gap-6 lg:grid-cols-2">


            {{-- Produtos --}}
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">

                <div class="border-b border-zinc-200 p-5 dark:border-zinc-700">

                    <flux:heading size="lg">
                        Produtos mais vendidos
                    </flux:heading>

                    <flux:text class="mt-1">
                        Os produtos com maior quantidade de vendas.
                    </flux:text>

                </div>

                <div class="divide-y divide-zinc-200 dark:divide-zinc-700">

                    @forelse ($topProducts as $index => $product)

                        <div class="flex items-center justify-between p-4">

                            <div class="flex items-center gap-3">

                                <div class="flex size-8 items-center justify-center rounded-lg bg-zinc-100 text-sm font-medium dark:bg-zinc-800">
                                    {{ $index + 1 }}
                                </div>

                                <div>

                                    <flux:text class="font-medium">
                                        {{ $product->name }}
                                    </flux:text>

                                    <flux:text class="text-sm">
                                        {{ $product->quantity }} unidades
                                    </flux:text>

                                </div>

                            </div>

                            <flux:text class="font-medium">
                                R$ {{ number_format($product->revenue, 2, ',', '.') }}
                            </flux:text>

                        </div>

                    @empty

                        <div class="p-8 text-center">

                            <flux:text>
                                Nenhuma venda registrada.
                            </flux:text>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- Categorias --}}
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">

                <div class="border-b border-zinc-200 p-5 dark:border-zinc-700">

                    <flux:heading size="lg">
                        Categorias mais vendidas
                    </flux:heading>

                    <flux:text class="mt-1">
                        Desempenho das categorias do cardápio.
                    </flux:text>

                </div>

                <div class="divide-y divide-zinc-200 dark:divide-zinc-700">

                    @forelse ($topCategories as $index => $category)

                        <div class="flex items-center justify-between p-4">

                            <div class="flex items-center gap-3">

                                <div class="flex size-8 items-center justify-center rounded-lg bg-zinc-100 text-sm font-medium dark:bg-zinc-800">
                                    {{ $index + 1 }}
                                </div>

                                <div>

                                    <flux:text class="font-medium">
                                        {{ $category->name }}
                                    </flux:text>

                                    <flux:text class="text-sm">
                                        {{ $category->quantity }} unidades
                                    </flux:text>

                                </div>

                            </div>

                            <flux:text class="font-medium">
                                R$ {{ number_format($category->revenue, 2, ',', '.') }}
                            </flux:text>

                        </div>

                    @empty

                        <div class="p-8 text-center">

                            <flux:text>
                                Nenhuma venda registrada.
                            </flux:text>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Pedidos por mês e horários --}}
        <div class="grid gap-6 lg:grid-cols-2">


            {{-- Pedidos por mês --}}
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">

                <div class="border-b border-zinc-200 p-5 dark:border-zinc-700">

                    <flux:heading size="lg">
                        Pedidos por mês
                    </flux:heading>

                    <flux:text class="mt-1">
                        Volume de pedidos no período analisado.
                    </flux:text>

                </div>

                <div class="p-5">

                    @forelse ($ordersByMonth as $month)

                        <div class="mb-4 last:mb-0">

                            <div class="mb-1 flex justify-between">

                                <flux:text class="text-sm">
                                    {{ \Carbon\Carbon::parse($month->month)->translatedFormat('F/Y') }}
                                </flux:text>

                                <flux:text class="text-sm font-medium">
                                    {{ $month->total }} pedidos
                                </flux:text>

                            </div>

                            @php
                                $maxOrders = $ordersByMonth->max('total') ?: 1;
                                $percentage = ($month->total / $maxOrders) * 100;
                            @endphp

                            <div class="h-3 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">

                                <div
                                    class="h-full rounded-full bg-zinc-900 dark:bg-zinc-100"
                                    style="width: {{ $percentage }}%"
                                ></div>

                            </div>

                        </div>

                    @empty

                        <div class="py-8 text-center">

                            <flux:text>
                                Nenhum pedido registrado.
                            </flux:text>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- Horários --}}
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">

                <div class="border-b border-zinc-200 p-5 dark:border-zinc-700">

                    <flux:heading size="lg">
                        Horários de maior movimento
                    </flux:heading>

                    <flux:text class="mt-1">
                        Quantidade de pedidos por horário.
                    </flux:text>

                </div>

                <div class="p-5">

                    @forelse ($ordersByHour as $hour)

                        <div class="mb-4 last:mb-0">

                            <div class="mb-1 flex justify-between">

                                <flux:text class="text-sm">
                                    {{ str_pad((int) $hour->hour, 2, '0', STR_PAD_LEFT) }}:00
                                </flux:text>

                                <flux:text class="text-sm font-medium">
                                    {{ $hour->total }} pedidos
                                </flux:text>

                            </div>

                            @php
                                $maxHourOrders = $ordersByHour->max('total') ?: 1;
                                $percentage = ($hour->total / $maxHourOrders) * 100;
                            @endphp

                            <div class="h-3 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">

                                <div
                                    class="h-full rounded-full bg-zinc-900 dark:bg-zinc-100"
                                    style="width: {{ $percentage }}%"
                                ></div>

                            </div>

                        </div>

                    @empty

                        <div class="py-8 text-center">

                            <flux:text>
                                Nenhum pedido registrado.
                            </flux:text>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Mesas --}}
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-900 dark:bg-zinc-900">

            <div class="border-b border-zinc-200 p-5 dark:border-zinc-700">

                <flux:heading size="lg">
                    Utilização das mesas
                </flux:heading>

                <flux:text class="mt-1">
                    Mesas com maior quantidade de pedidos.
                </flux:text>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="border-b border-zinc-200 dark:border-zinc-700">

                        <tr class="text-left">

                            <th class="px-5 py-3 font-medium">
                                Mesa
                            </th>

                            <th class="px-5 py-3 font-medium">
                                Pedidos
                            </th>

                            <th class="px-5 py-3 text-right font-medium">
                                Faturamento
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse ($tablesUsage as $table)

                            <tr>

                                <td class="px-5 py-3">
                                    Mesa {{ $table->number }}
                                </td>

                                <td class="px-5 py-3">
                                    {{ $table->total_orders }}
                                </td>

                                <td class="px-5 py-3 text-right">
                                    R$ {{ number_format($table->revenue, 2, ',', '.') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="px-5 py-8 text-center"
                                >
                                    Nenhuma utilização registrada.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-layouts::app>