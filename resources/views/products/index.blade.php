<x-layouts::app :title="'Produtos'">
    <div class="flex flex-col gap-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">
                    Produtos
                </h1>

                <p class="text-sm text-zinc-600">
                    {{ $establishment->name }}
                </p>
            </div>

            <a
                href="{{ route('establishments.products.create', $establishment) }}"
                class="rounded-lg bg-black px-4 py-2 text-sm font-medium text-white"
            >
                Novo produto
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($products->isEmpty())
            <div class="rounded-xl border border-zinc-200 bg-white p-6">
                <p class="text-zinc-600">
                    Nenhum produto cadastrado.
                </p>
            </div>
        @else
            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-zinc-200 bg-zinc-50">
                        <tr>
                            <th class="px-4 py-3">Produto</th>
                            <th class="px-4 py-3">Categoria</th>
                            <th class="px-4 py-3">Preço</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $product)
                            <tr class="border-b border-zinc-100 last:border-0">
                                <td class="px-4 py-3">
                                    <div class="font-medium">
                                        {{ $product->name }}
                                    </div>

                                    @if ($product->description)
                                        <div class="text-xs text-zinc-500">
                                            {{ $product->description }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    {{ $product->category->name }}
                                </td>

                                <td class="px-4 py-3">
                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($product->active)
                                        <span class="text-green-600">
                                            Ativo
                                        </span>
                                    @else
                                        <span class="text-zinc-500">
                                            Inativo
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex gap-3">
                                        <a
                                            href="{{ route('establishments.products.edit', [$establishment, $product]) }}"
                                            class="text-blue-600 hover:underline"
                                        >
                                            Editar
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('establishments.products.destroy', [$establishment, $product]) }}"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-red-600 hover:underline"
                                                onclick="return confirm('Deseja excluir este produto?')"
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