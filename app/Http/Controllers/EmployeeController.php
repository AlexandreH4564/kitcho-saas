<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Establishment $establishment): View
    {
        $this->authorizeAccess($establishment);

        $employees = $establishment->users()
            ->orderBy('name')
            ->get();

        return view('establishments.employees.index', compact(
            'establishment',
            'employees'
        ));
    }

    public function create(Establishment $establishment): View
    {
        $this->authorizeAccess($establishment);

        return view('establishments.employees.create', compact(
            'establishment'
        ));
    }

    public function store(
        Request $request,
        Establishment $establishment
    ): RedirectResponse {
        $this->authorizeAccess($establishment);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in(['manager', 'employee']),
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $establishment->users()->attach($user->id, [
            'role' => $validated['role'],
        ]);

        return redirect()
            ->route('establishments.employees.index', $establishment)
            ->with('success', 'Funcionário adicionado com sucesso.');
    }

    public function edit(
        Establishment $establishment,
        User $employee
    ): View {
        $this->authorizeAccess($establishment);
        $this->authorizeEmployeeManagement($establishment, $employee);

        return view('establishments.employees.edit', compact(
            'establishment',
            'employee'
        ));
    }

    public function update(
        Request $request,
        Establishment $establishment,
        User $employee
    ): RedirectResponse {
        $this->authorizeAccess($establishment);
        $this->authorizeEmployeeManagement($establishment, $employee);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($employee->id),
            ],

            'role' => [
                'required',
                Rule::in(['manager', 'employee']),
            ],
        ]);

        $employee->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $establishment->users()->updateExistingPivot(
            $employee->id,
            ['role' => $validated['role']]
        );

        return redirect()
            ->route('establishments.employees.index', $establishment)
            ->with('success', 'Funcionário atualizado com sucesso.');
    }

    public function destroy(
        Establishment $establishment,
        User $employee
    ): RedirectResponse {
        $this->authorizeAccess($establishment);
        $this->authorizeEmployeeManagement($establishment, $employee);

        $establishment->users()->detach($employee->id);

        return redirect()
            ->route('establishments.employees.index', $establishment)
            ->with('success', 'Funcionário removido do estabelecimento.');
    }

    private function authorizeAccess(Establishment $establishment): void
    {
        abort_unless(
            auth()->user()
                ->establishments()
                ->whereKey($establishment->id)
                ->exists(),
            403
        );
    }

    private function authorizeEmployeeManagement(
        Establishment $establishment,
        User $employee
    ): void {
        $membership = $establishment->users()
            ->whereKey($employee->id)
            ->first();

        abort_unless($membership, 404);

        $employeeRole = $membership->pivot->role;

        $currentUserRole = auth()->user()
            ->establishments()
            ->whereKey($establishment->id)
            ->first()
            ->pivot
            ->role;

        if (
            $employeeRole === 'owner' &&
            $currentUserRole !== 'owner'
        ) {
            abort(403);
        }
    }
}