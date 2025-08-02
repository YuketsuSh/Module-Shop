@extends('admin.layouts.admin')

@section('title', 'Ajouter un produit')

@section('content')
    @include('shop::admin.products.form', [
        'product' => $product,
        'categories' => $categories,
        'tags' => $tags,
        'editing' => false
    ])
@endsection
