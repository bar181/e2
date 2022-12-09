@extends('templates/master')

@section('content')
    @include('instructions')

    <div class="my-2 py-4 card ">
        <div test="wager-page" class="my-2 d-flex justify-content-center">
            <div class="text-center">
                <h2>{{ $player['name'] }}, Set your Wager </h2>
                <h2>You have ${{ $player['cash'] }}</h2>
            </div>
        </div>

        <form method="POST" action="post_wager">
            <div class="my-2 pt-5 fs-3 d-flex justify-content-center">
                <div class="form-check mx-3">
                    <input test="wager-w10 " class="form-check-input" type="radio" name="wager" id="w10"
                        value="10" checked>
                    <label class="form-check-label" for="w10">
                        $10
                    </label>
                </div>
                <div class="form-check mx-3 ">
                    @if ($player['cash'] >= 50)
                        <input test="wager-w50" class="form-check-input" type="radio" name="wager" id="w50"
                            value="50">
                        <label class="form-check-label" for="w50">
                            $50
                        </label>
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <input type="hidden" id="player_id" name="player_id" value="{{ $player['id'] }}">
                <button test="submit-wager" type="submit" class="btn btn-primary mb-3 fs-1">Let's Deal !</button>
            </div>
        </form>

    </div>
@endsection
