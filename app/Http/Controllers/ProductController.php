<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Establishment;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Establishment $establishment): View
    {
        $this->authorizeEstablishment($establishment);

        $products = $establishment->products()
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('products.index', compact('establishment', 'products'));
    }

    public function create(Establishment $establishment): View
    {
        $this->authorizeEstablishment($establishment);

        $categories = $establishment->categories()
            ->orderBy('name')
            ->get();

        return view('products.create', compact('establishment', 'categories'));
    }

    public function store(
        Request $request,
        Establishment $establishment
    ): RedirectResponse {
        $this->authorizeEstablishment($establishment);

        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'active' => [
                'boolean',
            ],
        ]);

        $category = $establishment->categories()
            ->whereKey($validated['category_id'])
            ->firstOrFail();

        $establishment->products()->create([
            'category_id' => $category->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('establishments.products.index', $establishment)
            ->with('success', 'Produto criado com sucesso.');
    }

    public function edit(
        Establishment $establishment,
        Product $product
    ): View {
        $this->authorizeEstablishment($establishment);
        $this->authorizeProduct($establishment, $product);

        $categories = $establishment->categories()
            ->orderBy('name')
            ->get();

        return view('products.edit', compact(
            'establishment',
            'product',
            'categories'
        ));
    }

    public function update(
        Request $request,
        Establishment $establishment,
        Product $product
    ): RedirectResponse {
        $this->authorizeEstablishment($establishment);
        $this->authorizeProduct($establishment, $product);

        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'active' => [
                'boolean',
            ],
        ]);

        $category = $establishment->categories()
            ->whereKey($validated['category_id'])
            ->firstOrFail();

        $product->update([
            'category_id' => $category->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('establishments.products.index', $establishment)
            ->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(
        Establishment $establishment,
        Product $product
    ): RedirectResponse {
        $this->authorizeEstablishment($establishment);
        $this->authorizeProduct($establishment, $product);

        $product->delete();

        return redirect()
            ->route('establishments.products.index', $establishment)
            ->with('success', 'Produto excluído com sucesso.');
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

    private function authorizeProduct(
        Establishment $establishment,
        Product $product
    ): void {
        abort_unless(
            $product->establishment_id === $establishment->id,
            404
        );
    }
}