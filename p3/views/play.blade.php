@extends('templates/master')

@section('content')
    @include('instructions')
    <div class="d-flex justify-content-center">
        @include('play-dealer')

        @include('play-player')

        @if ($player['multiplayer'] > 0)
            @include('play-ai')
        @endif
    </div>

    <div class="d-flex justify-content-center">
        @if ($player['play'] != 'done')
            @include('play-options')
        @else
            @if ($player['cash'] > 0)
                @include('play-over')
            @else
                @include('game-stats')
                @include('game-over')
            @endif
        @endif
    </div>
@endsection
