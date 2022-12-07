@extends('templates/master')

@section('content')
    @include('instructions')

    <form method="POST" action="post_play">


        <div class="">
            <h2>PLAY</h2>
        </div>



        <div class="d-flex justify-content-center">
            <input type="text" id="player_id" name="player_id" value="{{ $player['id'] }}">
            <button type="submit" class="btn btn-primary mb-3">Deal</button>
        </div>
    </form>
@endsection
