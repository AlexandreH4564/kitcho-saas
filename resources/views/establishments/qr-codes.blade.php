<x-layouts::app :title="'QR Codes - ' . $establishment->name">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold">
                QR Codes das mesas
            </h1>

            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                {{ $establishment->name }}
            </p>
        </div>

        @if ($tableQrs->isEmpty())
            <div class="rounded-xl border border-dashed p-8 text-center">
                <p class="text-zinc-600 dark:text-zinc-400">
                    Este estabelecimento ainda não possui mesas cadastradas.
                </p>

                <a
                    href="{{ route('establishments.tables.index', $establishment) }}"
                    class="mt-4 inline-block rounded-lg bg-black px-4 py-2 text-sm font-medium text-white"
                >
                    Cadastrar mesas
                </a>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($tableQrs as $tableQr)
                    <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold">
                                Mesa {{ $tableQr['table']->number }}
                            </h2>

                            <p class="text-sm text-zinc-500">
                                QR Code da mesa
                            </p>
                        </div>

                        <div class="flex justify-center rounded-lg bg-white p-4">
                            {!! QrCode::size(220)->generate($tableQr['url']) !!}
                        </div>

                        <div class="mt-4 break-all rounded-lg bg-zinc-100 p-3 text-xs text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                            {{ $tableQr['url'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts::app>