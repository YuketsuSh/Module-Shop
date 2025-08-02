<?php

namespace Modules\Shop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Shop\Models\Product;
use Modules\Shop\Models\ProductVersion;

class ProductVersionController extends Controller
{
    public function index(Product $product)
    {
        $versions = $product->versions()->latest()->get();

        return view('shop::admin.products.versions.index', compact('product', 'versions'));
    }

    public function create(Product $product)
    {
        return view('shop::admin.products.versions.create', [
            'product' => $product,
            'version' => new ProductVersion(),
            'editing' => false,
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'version' => 'required|string|max:255',
            'file' => 'required|file',
            'changelog' => 'nullable|string',
            'ttl' => 'nullable|integer|min:0',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $cleanName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

        $version = $product->versions()->create([
            'version' => $request->version,
            'changelog' => $request->changelog,
            'ttl' => $request->ttl,
            'file_path' => '', // temporaire
            'file_hash' => '',
        ]);

        $media = $version
            ->addMedia($file)
            ->preservingOriginal()
            ->usingFileName($cleanName)
            ->withCustomProperties(['original_name' => $originalName])
            ->toMediaCollection('version_files', 'public');

        $version->update([
            'file_path' => $media->getPath(),
            'file_hash' => hash_file('sha256', $media->getPath()),
        ]);

        return redirect()
            ->route('admin.shop.products.versions.index', $product)
            ->with('success', 'Version ajoutée avec succès.');
    }




    public function edit(Product $product, ProductVersion $version)
    {
        return view('shop::admin.products.versions.edit', [
            'product' => $product,
            'version' => $version,
            'editing' => true,
        ]);
    }

    public function update(Request $request, Product $product, ProductVersion $version)
    {
        $request->validate([
            'version' => 'required|string|max:255',
            'file' => 'nullable|file',
            'changelog' => 'nullable|string',
            'ttl' => 'nullable|integer|min:0',
        ]);

        $data = [
            'version' => $request->version,
            'changelog' => $request->changelog,
            'ttl' => $request->ttl,
        ];

        if ($request->hasFile('file')) {
            $version->clearMediaCollection('version_files');

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $cleanName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            $media = $version
                ->addMedia($file)
                ->preservingOriginal()
                ->usingFileName($cleanName)
                ->withCustomProperties(['original_name' => $originalName])
                ->toMediaCollection('version_files', 'public');

            $data['file_path'] = $media->getPath();
            $data['file_hash'] = hash_file('sha256', $media->getPath());
        }

        $version->update($data);

        return redirect()
            ->route('admin.shop.products.versions.index', $product)
            ->with('success', 'Version mise à jour.');
    }




    public function destroy(Product $product, ProductVersion $version)
    {
        Storage::delete($version->file_path);
        $version->delete();

        return back()->with('success', 'Version supprimée.');
    }

    public function download(Product $product, ProductVersion $version)
    {
        $media = $version->getFirstMedia('version_files');

        if (!$media) {
            abort(404, 'Fichier introuvable pour cette version.');
        }

        if ($version->ttl && $version->created_at->addDays($version->ttl)->isPast()) {
            return back()->withErrors('La période de téléchargement pour cette version est expirée.');
        }

        return response()->download($media->getPath(), $media->file_name);
    }

}
