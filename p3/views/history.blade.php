@extends('templates/master')

@section('title')
    Round History
@endsection

@section('content')
    <div class="d-flex justify-content-center me-4">
        @include('game-stats')
    </div>

    <h2> Round History</h2>

    <ul>
        @foreach ($rounds as $round)
            <li><a test="round-link" href="/round?id={{ $round['id'] }}">Round: {{ $round['id'] }} on
                    {{ $round['timestamp'] }}</a></li>
        @endforeach
    </ul>

    <a class="button btn btn-info" href="/">Back to Home</a>
@endsection
