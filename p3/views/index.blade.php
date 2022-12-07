@extends('templates/master')

@section('content')
    @include('instructions')

    @if ($app->errorsExist())
        <ul test="product-added-error" class="error alert alert-danger">
            Errors - All fields are required
        </ul>
    @endif

    <form method="POST" action="/post_setup">
        https://github.com/selfthinker/CSS-Playing-Cards
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
                <input test="multiPlayer" class="form-check-input" type="checkbox" id="multiPlayer" name="multiPlayer"
                    checked>
                <label class="form-check-label" for="multiPlayer">Multi Player Game</label>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <button test="submit-player" type="submit" class="btn btn-primary mb-3">Get Started</button>
        </div>
    </form>

    @if ($app->errorsExist())
        <ul class="error alert alert-danger px-4">
            @foreach ($app->errors() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
@endsection
