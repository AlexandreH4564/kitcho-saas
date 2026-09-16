<x-layouts::app :title="'Editar produto'">
    <div class="mx-auto max-w-2xl">

        <h1 class="mb-6 text-2xl font-semibold">
            Editar produto
        </h1>

        <form
            method="POST"
            action="{{ route('establishments.products.update', [$establishment, $product]) }}"
            class="flex flex-col gap-6"
        >
            @csrf
            @method('PUT')

            <flux:input
                name="name"
                label="Nome"
                :value="old('name', $product->name)"
                required
                autofocus
            />

            <div>
                <label
                    for="category_id"
                    class="mb-2 block text-sm font-medium"
                >
                    Categoria
                </label>

                <select
                    id="category_id"
                    name="category_id"
                    required
                    class="w-full rounded-lg border border-zinc-300 px-3 py-2"
                >
                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected(old('category_id', $product->category_id) == $category->id)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <flux:textarea
                name="description"
                label="Descrição"
            >{{ old('description', $product->description) }}</flux:textarea>

            <flux:input
                name="price"
                label="Preço"
                type="number"
                step="0.01"
                min="0"
                :value="old('price', $product->price)"
                required
            />

            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    name="active"
                    value="1"
                    @checked(old('active', $product->active))
                    class="rounded"
                >

                <span class="text-sm">
                    Produto ativo
                </span>
            </label>

            <div class="flex gap-3">
                <flux:button
                    type="submit"
                    variant="primary"
                >
                    Salvar alterações
                </flux:button>

                <flux:button
                    href="{{ route('establishments.products.index', $establishment) }}"
                >
                    Cancelar
                </flux:button>
            </div>
        </form>

    </div>
</x-layouts::app>