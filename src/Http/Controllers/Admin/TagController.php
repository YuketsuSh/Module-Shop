<?php

namespace Modules\Shop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Shop\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('name')->get();
        return view('shop::admin.tags.index', compact('tags'));
    }

    public function create(){
        return view('shop::admin.tags.create', [
            'tag' => new Tag(),
            'editing' => false,
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:shop_tags,slug|max:255',
        ]);

        $slug = $request->slug ?? Str::slug($request->name);

        Tag::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.shop.tags.index')->with('success', 'Tag crée avec succès.');
    }

    public function edit(Tag $tag){
        return view('shop::admin.tags.edit', [
            'tag' => $tag,
            'editing' => true,
        ]);
    }

    public function update(Request $request, Tag $tag){
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:shop_tags,slug,' . $tag->id,
        ]);

        $slug = $request->slug ?: Str::slug($request->name);

        $tag->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.shop.tags.index')->with('success', 'Tag mis à jour');
    }

    public function destroy(Tag $tag){
        $tag->delete();
        return response()->json(['success' => true]);
    }

}
