<x-layouts::app>
<div class="max-w-6xl mx-auto p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">
                Funcionários
            </h1>

            <p class="text-gray-600">
                {{ $establishment->name }}
            </p>
        </div>

        <a
            href="{{ route('establishments.employees.create', $establishment) }}"
            class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800"
        >
            + Adicionar funcionário
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-6 py-4">Nome</th>
                    <th class="text-left px-6 py-4">E-mail</th>
                    <th class="text-left px-6 py-4">Função</th>
                    <th class="text-right px-6 py-4">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($employees as $employee)
                    <tr class="border-t">
                        <td class="px-6 py-4">
                            {{ $employee->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $employee->email }}
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $role = $employee->pivot->role;

                                $roleLabels = [
                                    'owner' => 'Proprietário',
                                    'manager' => 'Gerente',
                                    'employee' => 'Funcionário',
                                ];
                            @endphp

                            {{ $roleLabels[$role] ?? $role }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-3">
                                <a
                                    href="{{ route('establishments.employees.edit', [$establishment, $employee]) }}"
                                    class="text-blue-600 hover:underline"
                                >
                                    Editar
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('establishments.employees.destroy', [$establishment, $employee]) }}"
                                    onsubmit="return confirm('Remover este funcionário do estabelecimento?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="text-red-600 hover:underline"
                                    >
                                        Remover
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td
                            colspan="4"
                            class="px-6 py-10 text-center text-gray-500"
                        >
                            Nenhum funcionário cadastrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</x-layouts::app>