<x-layouts::app :title="'Categorias'">
    <div class="flex flex-col gap-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">
                    Categorias
                </h1>

                <p class="text-sm text-zinc-600">
                    {{ $establishment->name }}
                </p>
            </div>

            <a
                href="{{ route('establishments.categories.create', $establishment) }}"
                class="rounded-lg bg-black px-4 py-2 text-sm font-medium text-white"
            >
                Nova categoria
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($categories->isEmpty())
            <div class="rounded-lg border p-6 text-center">
                <p class="text-zinc-600">
                    Nenhuma categoria cadastrada.
                </p>
            </div>
        @else
            <div class="rounded-lg border">
                <div class="divide-y">
                    @foreach ($categories as $category)
                        <div class="flex items-center justify-between p-4">
                            <div>
                                <p class="font-medium">
                                    {{ $category->name }}
                                </p>

                                <p class="text-sm text-zinc-500">
                                    {{ $category->products()->count() }} produto(s)
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ route('establishments.categories.edit', [$establishment, $category]) }}"
                                    class="rounded-lg border px-3 py-2 text-sm"
                                >
                                    Editar
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('establishments.categories.destroy', [$establishment, $category]) }}"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded-lg border px-3 py-2 text-sm"
                                        onclick="return confirm('Deseja excluir esta categoria?')"
                                    >
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-layouts::app>