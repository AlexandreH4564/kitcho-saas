<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\RestaurantTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TableController extends Controller
{
    public function index(Establishment $establishment): View
    {
        $this->authorizeEstablishment($establishment);

        $tables = $establishment->tables()
            ->orderBy('number')
            ->get();

        return view('tables.index', compact('establishment', 'tables'));
    }

    public function create(Establishment $establishment): View
    {
        $this->authorizeEstablishment($establishment);

        return view('tables.create', compact('establishment'));
    }

    public function store(
        Request $request,
        Establishment $establishment
    ): RedirectResponse {
        $this->authorizeEstablishment($establishment);

        $validated = $request->validate([
            'number' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $exists = $establishment->tables()
            ->where('number', $validated['number'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'number' => 'Este número de mesa já está cadastrado.',
                ])
                ->withInput();
        }

        $establishment->tables()->create([
            'number' => $validated['number'],
            'status' => 'available',
        ]);

        return redirect()
            ->route('establishments.tables.index', $establishment)
            ->with('success', 'Mesa criada com sucesso.');
    }

    public function edit(
        Establishment $establishment,
        RestaurantTable $table
    ): View {
        $this->authorizeEstablishment($establishment);
        $this->authorizeTable($establishment, $table);

        return view('tables.edit', compact('establishment', 'table'));
    }

    public function update(
        Request $request,
        Establishment $establishment,
        RestaurantTable $table
    ): RedirectResponse {
        $this->authorizeEstablishment($establishment);
        $this->authorizeTable($establishment, $table);

        $validated = $request->validate([
            'number' => [
                'required',
                'integer',
                'min:1',
            ],
            'status' => [
                'required',
                'in:available,occupied',
            ],
        ]);

        $exists = $establishment->tables()
            ->where('number', $validated['number'])
            ->where('id', '!=', $table->id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'number' => 'Este número de mesa já está cadastrado.',
                ])
                ->withInput();
        }

        $table->update($validated);

        return redirect()
            ->route('establishments.tables.index', $establishment)
            ->with('success', 'Mesa atualizada com sucesso.');
    }

    public function destroy(
        Establishment $establishment,
        RestaurantTable $table
    ): RedirectResponse {
        $this->authorizeEstablishment($establishment);
        $this->authorizeTable($establishment, $table);

        $table->delete();

        return redirect()
            ->route('establishments.tables.index', $establishment)
            ->with('success', 'Mesa excluída com sucesso.');
    }

    private function authorizeEstablishment(
        Establishment $establishment
    ): void {
        abort_unless(
            auth()->user()
                ->establishments()
                ->whereKey($establishment->id)
                ->exists(),
            403
        );
    }

    private function authorizeTable(
        Establishment $establishment,
        RestaurantTable $table
    ): void {
        abort_unless(
            $table->establishment_id === $establishment->id,
            404
        );
    }
}