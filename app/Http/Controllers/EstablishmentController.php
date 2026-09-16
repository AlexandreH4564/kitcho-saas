<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EstablishmentController extends Controller
{
    public function index(): View
    {
        $establishments = auth()->user()
            ->establishments()
            ->latest()
            ->get();

        return view('establishments.index', compact('establishments'));
    }

    public function create(): View
    {
        return view('establishments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
        ]);

        $establishment = Establishment::create([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'address' => $validated['address'],
        ]);

        auth()->user()->establishments()->attach($establishment->id, [
            'role' => 'owner',
        ]);

        return redirect()
            ->route('establishments.index')
            ->with('success', 'Estabelecimento criado com sucesso.');
    }

    public function show(Establishment $establishment): View
    {
        return view('establishments.show', compact('establishment'));
    }

    public function edit(Establishment $establishment): View
    {
        return view('establishments.edit', compact('establishment'));
    }

    public function update(Request $request, Establishment $establishment): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
        ]);

        $establishment->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
        ]);

        return redirect()
            ->route('establishments.index')
            ->with('success', 'Estabelecimento atualizado com sucesso.');
    }

    public function destroy(Establishment $establishment): RedirectResponse
    {
        $establishment->delete();

        return redirect()
            ->route('establishments.index')
            ->with('success', 'Estabelecimento excluído com sucesso.');
    }

    private function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Establishment::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }
}