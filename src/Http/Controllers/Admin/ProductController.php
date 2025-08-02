<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Shop\Http\Requests\ProductRequest;
use Modules\Shop\Models\Category;
use Modules\Shop\Models\Product;
use Modules\Shop\Models\Tag;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('shop::admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('shop::admin.products.form', [
            'product' => new Product(),
            'categories' => $categories,
            'tags' => $tags,
            'editing' => false,
        ]);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $product = Product::create($data);
        $product->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.shop.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('shop::admin.products.form', [
            'product' => $product->load('tags'),
            'categories' => $categories,
            'tags' => $tags,
            'editing' => true,
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $product->update($data);
        $product->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.shop.products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produit supprimé.');
    }
}
