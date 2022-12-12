<div test="ai-play" class="col-3 card m-1 p-2 text-center">
    <h2>Player</h2>
    <div class="fs-4">
        Points: {{ $round['ai']['score'] }}
    </div>
    <hr>
    <div class="d-flex flex-wrap justify-content-center">
        @foreach ($round['ai']['cards'] as $card)
            <span class="showcard fs-4 {{ $card['style'] }}"> {!! $card['show'] !!} </span>
        @endforeach
    </div>

    @if (!is_null($round['ai']['result']))
        <hr>
        <div class="fs-4">
            Result: {{ $round['ai']['result'] }}
        </div>
    @endif
</div>
