<x-layouts::app>
<div class="max-w-2xl mx-auto p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold">
            Adicionar funcionário
        </h1>

        <p class="text-gray-600">
            {{ $establishment->name }}
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('establishments.employees.store', $establishment) }}"
        class="bg-white rounded-xl shadow p-6 space-y-5"
    >
        @csrf

        <div>
            <label class="block font-medium mb-2">
                Nome
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                class="w-full rounded-lg border-gray-300"
            >
        </div>

        <div>
            <label class="block font-medium mb-2">
                E-mail
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="w-full rounded-lg border-gray-300"
            >
        </div>

        <div>
            <label class="block font-medium mb-2">
                Senha
            </label>

            <input
                type="password"
                name="password"
                required
                class="w-full rounded-lg border-gray-300"
            >
        </div>

        <div>
            <label class="block font-medium mb-2">
                Confirmar senha
            </label>

            <input
                type="password"
                name="password_confirmation"
                required
                class="w-full rounded-lg border-gray-300"
            >
        </div>

        <div>
            <label class="block font-medium mb-2">
                Função
            </label>

            <select
                name="role"
                required
                class="w-full rounded-lg border-gray-300"
            >
                <option value="">Selecione</option>

                <option value="owner" @selected(old('role') === 'owner')>
                    Proprietário
                </option>

                <option value="manager" @selected(old('role') === 'manager')>
                    Gerente
                </option>

                <option value="employee" @selected(old('role') === 'employee')>
                    Funcionário
                </option>
            </select>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a
                href="{{ route('establishments.employees.index', $establishment) }}"
                class="px-4 py-2 border rounded-lg"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="px-4 py-2 bg-black text-white rounded-lg"
            >
                Criar funcionário
            </button>
        </div>
    </form>

</div>
</x-layouts::app>