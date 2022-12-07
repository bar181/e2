@extends('templates/master')

@section('title')
    Round Details
@endsection

@section('content')
    <h2> Round Details</h2>

    <ul>
        <li>Round {{ $round['id'] }}</li>
        <li>Wager ${{ $round['wager'] }}</li>
        <li class="fw-bold">Results {{ $round['result'] }}</li>
        <li>Timestamp {{ $round['timestamp'] }}</li>
    </ul>

    <a class="button btn btn-info" href="/history">Back to Round History</a>
@endsection
