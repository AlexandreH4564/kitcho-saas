<x-layouts::app :title="'Mesas'">
    <div class="flex flex-col gap-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">
                    Mesas
                </h1>

                <p class="text-sm text-zinc-600">
                    {{ $establishment->name }}
                </p>
            </div>

            <a
                href="{{ route('establishments.tables.create', $establishment) }}"
                class="rounded-lg bg-black px-4 py-2 text-sm font-medium text-white"
            >
                Nova mesa
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($tables->isEmpty())
            <div class="rounded-xl border border-zinc-200 bg-white p-6">
                <p class="text-zinc-600">
                    Nenhuma mesa cadastrada.
                </p>
            </div>
        @else
            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-zinc-200 bg-zinc-50">
                        <tr>
                            <th class="px-4 py-3">Mesa</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($tables as $table)
                            <tr class="border-b border-zinc-100 last:border-0">
                                <td class="px-4 py-3 font-medium">
                                    Mesa {{ $table->number }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($table->status === 'available')
                                        <span class="text-green-600">
                                            Disponível
                                        </span>
                                    @else
                                        <span class="text-orange-600">
                                            Ocupada
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex gap-3">
                                        <a
                                            href="{{ route('establishments.tables.edit', [$establishment, $table]) }}"
                                            class="text-blue-600 hover:underline"
                                        >
                                            Editar
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('establishments.tables.destroy', [$establishment, $table]) }}"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-red-600 hover:underline"
                                                onclick="return confirm('Deseja excluir esta mesa?')"
                                            >
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</x-layouts::app>