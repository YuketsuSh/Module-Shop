@extends('admin.layouts.admin')

@section('title', 'Modifier le produit')

@section('content')
    @include('shop::admin.products.form', [
        'product' => $product,
        'categories' => $categories,
        'tags' => $tags,
        'editing' => true
    ])
@endsection
