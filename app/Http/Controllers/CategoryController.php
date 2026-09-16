<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Establishment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Establishment $establishment): View
    {
        $this->authorizeEstablishment($establishment);

        $categories = $establishment->categories()
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('establishment', 'categories'));
    }

    public function create(Establishment $establishment): View
    {
        $this->authorizeEstablishment($establishment);

        return view('categories.create', compact('establishment'));
    }

    public function store(Request $request, Establishment $establishment): RedirectResponse
    {
        $this->authorizeEstablishment($establishment);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $establishment->categories()->create($validated);

        return redirect()
            ->route('establishments.categories.index', $establishment)
            ->with('success', 'Categoria criada com sucesso.');
    }

    public function edit(Establishment $establishment, Category $category): View
    {
        $this->authorizeEstablishment($establishment);
        $this->authorizeCategory($establishment, $category);

        return view('categories.edit', compact('establishment', 'category'));
    }

    public function update(
        Request $request,
        Establishment $establishment,
        Category $category
    ): RedirectResponse {
        $this->authorizeEstablishment($establishment);
        $this->authorizeCategory($establishment, $category);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category->update($validated);

        return redirect()
            ->route('establishments.categories.index', $establishment)
            ->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(
        Establishment $establishment,
        Category $category
    ): RedirectResponse {
        $this->authorizeEstablishment($establishment);
        $this->authorizeCategory($establishment, $category);

        $category->delete();

        return redirect()
            ->route('establishments.categories.index', $establishment)
            ->with('success', 'Categoria excluída com sucesso.');
    }

    private function authorizeEstablishment(Establishment $establishment): void
    {
        abort_unless(
            auth()->user()->establishments()->whereKey($establishment->id)->exists(),
            403
        );
    }

    private function authorizeCategory(
        Establishment $establishment,
        Category $category
    ): void {
        abort_unless(
            $category->establishment_id === $establishment->id,
            404
        );
    }
}