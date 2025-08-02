<?php

namespace Modules\Shop\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Shop\Http\Requests\ProductRequest;
use Modules\Shop\Models\Category;
use Modules\Shop\Models\Product;
use Modules\Shop\Models\Tag;
use Modules\Shop\Support\Currency;

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
        $currencies = Currency::all();


        return view('shop::admin.products.create', [
            'product' => new Product(),
            'categories' => $categories,
            'currencies' => $currencies,
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

        if ($product->type === 'digital' && $request->hasFile('file')) {
            $this->storeProductFile($product, $request->file('file'), $request->input('changelog'));
        }

        return redirect()->route('admin.shop.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $currencies = Currency::all();

        return view('shop::admin.products.edit', [
            'product' => $product->load('tags'),
            'categories' => $categories,
            'tags' => $tags,
            'currencies' => $currencies,
            'editing' => true,
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $product->update($data);
        $product->tags()->sync($request->input('tags', []));

        if ($product->type === 'digital' && $request->hasFile('file')) {
            $this->storeProductFile($product, $request->file('file'), $request->input('changelog'));
        }

        return redirect()->route('admin.shop.products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produit supprimé.');
    }

    protected function storeProductFile(Product $product, $file, ?string $changelog = null): void
    {
        $versionNumber = 'v' . ($product->versions()->count() + 1);
        $filename = $versionNumber . '_' . Str::slug($file->getClientOriginalName());
        $path = $file->storeAs("products/{$product->id}/versions", $filename);

        $product->versions()->create([
            'version' => $versionNumber,
            'changelog' => $changelog,
            'file_path' => $path,
            'file_hash' => hash_file('sha256', storage_path("app/{$path}")),
            'ttl' => null,
        ]);
    }
}
