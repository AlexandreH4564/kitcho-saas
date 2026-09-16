<x-layouts::app :title="'Nova categoria'">
    <div class="max-w-xl">

        <h1 class="mb-6 text-2xl font-semibold">
            Nova categoria
        </h1>

        <form
            method="POST"
            action="{{ route('establishments.categories.store', $establishment) }}"
            class="flex flex-col gap-6"
        >
            @csrf

            <div>
                <label for="name" class="mb-2 block text-sm font-medium">
                    Nome
                </label>

                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="w-full rounded-lg border px-3 py-2"
                    placeholder="Ex: Pizzas"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button
                    type="submit"
                    class="rounded-lg bg-black px-4 py-2 text-sm font-medium text-white"
                >
                    Criar categoria
                </button>

                <a
                    href="{{ route('establishments.categories.index', $establishment) }}"
                    class="rounded-lg border px-4 py-2 text-sm"
                >
                    Cancelar
                </a>
            </div>
        </form>

    </div>
</x-layouts::app>