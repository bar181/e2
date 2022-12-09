<div test="player-play" class="col-6 card m-1 p-2 text-center">
    <h2>Player</h2>
    <div class="fs-4">
        Points: <span test="player-score">{{ $round['player']['score'] }} </span>
    </div>
    <hr>
    <div class="d-flex flex-wrap justify-content-center">
        @foreach ($round['player']['cards'] as $card)
            <span class="showcard fs-4 {{ $card['style'] }}"> {!! $card['show'] !!} </span>
        @endforeach
    </div>
    @if (!is_null($round['player']['result']))
        <hr>
        <div class="fs-4">
            Result: {{ $round['player']['result'] }}
        </div>
    @endif
</div>
