@extends('templates/master')

@section('title')
    Add a Product
@endsection

@section('content')
    @if ($productSaved)
        <div test='product-added-confirmation' class='alert alert-success'>Thank you, your product was added! <a
                href='/product?sku={{ $sku }}'>You
                can view it here...</a></div>
    @endif
    @if ($app->errorsExist())
        <ul test='product-added-error' class='error alert alert-danger'>
            Errors - All fields are required
        </ul>
    @endif
    <div id='product-new' class="card p-3">
        <h2>Add a New Product</h2>


        <form method='POST' id='product-new' action='/products/save-product'>
            <div class='form-group'>
                <label for='name'>Name of Product</label>
                <input type='text' class='form-control' test='name' name='name' id='name'
                    value='{{ $app->old('name') }}'>
            </div>
            <div class='form-group'>
                <label for='sku'>SKU</label>
                <input type='text' class='form-control' test='sku' name='sku' id='sku'
                    value='{{ $app->old('sku') }}'>
            </div>
            <div class='form-group'>
                <label for='description'>Description</label>
                <textarea test='description' name='description' id='description' class='form-control'>{{ $app->old('description') }}</textarea>
            </div>
            <div class='form-group'>
                <label for='price'>price</label>
                <input type='text' test='price' class='form-control' name='price' id='price'
                    value='{{ $app->old('price') }}'>
            </div>
            <div class='form-group'>
                <label for='available'>available</label>
                <input type='available' test='available' class='form-control' name='available' id='available'
                    value='{{ $app->old('available') }}'>
            </div>
            <div class='form-group'>
                <label for='weight'>weight</label>
                <input type='weight' test='weight' class='form-control' name='weight' id='weight'
                    value='{{ $app->old('weight') }}'>
            </div>
            {{-- <div class='form-group'>
                <select class="form-select" name='perishable' id='perishable' aria-label="Default select example">
                    <option value="0" selected>Not Perishable</option>
                    <option value="1">Perishable</option>
                </select>
            </div> --}}
            <div class='form-group'>
                <input type='checkbox' test='perishable' name='perishable' id='perishable' value=1>
                <label for='perishable'>Perishable</label>
            </div>
            <button test='newproduct-submit-button' type='submit' class='btn btn-primary'>Add Product</button>
        </form>
    </div>

    @if ($app->errorsExist())
        <ul class='error alert alert-danger'>
            @foreach ($app->errors() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <a href='/products'>&larr; Return to all products</a>
@endsection
