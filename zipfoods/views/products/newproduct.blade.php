@extends('templates/master')

@section('title')
    Add a Product
@endsection

@section('content')
    @if ($app->errorsExist())
        <ul class='error alert alert-danger'>
            Errors - All fields are required
        </ul>
    @endif
    <div id='product-new'>
        <h2>Add a New Product</h2>
    </div>

    <form method='POST' id='product-new' action='/products/save-product'>
        <div class='form-group'>
            <label for='name'>Name of Product</label>
            <input type='text' class='form-control' name='name' id='name' value='{{ $app->old('name') }}'>
        </div>
        <div class='form-group'>
            <label for='sku'>SKU</label>
            <input type='text' class='form-control' name='sku' id='sku' value='{{ $app->old('sku') }}'>
        </div>
        <div class='form-group'>
            <label for='description'>Description</label>
            <textarea name='description' id='description' class='form-control'>{{ $app->old('description') }}</textarea>
        </div>
        <div class='form-group'>
            <label for='price'>price</label>
            <input type='text' class='form-control' name='price' id='price' value='{{ $app->old('price') }}'>
        </div>
        <div class='form-group'>
            <label for='available'>available</label>
            <input type='text' class='form-control' name='available' id='available' value='{{ $app->old('available') }}'>
        </div>
        <div class='form-group'>
            <label for='weight'>weight</label>
            <input type='text' class='form-control' name='weight' id='weight' value='{{ $app->old('weight') }}'>
        </div>
        <div class='form-group'>
            <select class="form-select" name='perishable' id='perishable' aria-label="Default select example">
                <option value="0" selected>Not Perishable</option>
                <option value="1">Perishable</option>
            </select>
        </div>
        <button type='submit' class='btn btn-primary'>Submit Review</button>
    </form>


    <a href='/products'>&larr; Return to all products</a>
@endsection
