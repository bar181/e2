@extends('templates/master')

@section('content')
    @include('instructions')

    @if ($app->errorsExist())
        <ul test="product-added-error" class="error alert alert-danger">
            Errors - All fields are required
        </ul>
    @endif
    <div class="d-flex justify-content-center">
        <div class="col-6 m-2">
            <img src="/images/dogs_poker.jpg" class="card-img-top" alt="A painting by Brad ">
            <div class="mt-5 text-center">
                <h2>E2 Project 2</h2>
                <h2>by Bradley Ross</h2>
            </div>
        </div>
        <div class="col-6 m-2">
            <div class="my-2 p-4 card ">
                <form method="POST" action="/post_setup">

                    <div class="my-4">
                        <label for="name" class="form-label">Your name</label>
                        <input test="name" type="text" class="form-control" name="name" id="name"
                            value="{{ $app->old('name') ?? $player['name'] }}">
                    </div>
                    <div class="mb-4">
                        <label for="cash" class="form-label">Starting Balance</label>
                        <input test="cash" type="text" class="form-control" name="cash" id="cash"
                            value="{{ $app->old('cash') ?? $player['cash'] }}">
                    </div>
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input test="multiPlayer" class="form-check-input" type="checkbox" id="multiPlayer"
                                name="multiPlayer" checked>
                            <label class="form-check-label" for="multiPlayer">Multi Player Game</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button test="submit-player" type="submit" class="btn btn-primary mb-3">Get Started</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if ($app->errorsExist())
        <ul class="error alert alert-danger px-4">
            @foreach ($app->errors() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
@endsection
