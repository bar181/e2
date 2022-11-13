@extends('templates/master')

@section('title')
    Product not found
@endsection

@section('content')
    <div id='product-show'>
        <h2>Product not found</h2>

        <p class='product-description'>
            Sorry unable to find "{{ $product }}"
        </p>

    </div>

    <a href='/products'>&larr; Check out our other amazing products</a>
@endsection
