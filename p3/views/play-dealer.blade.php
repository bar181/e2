<div test="dealer-play" class="col-3 card m-1 p-2 text-center">
    <h2>Dealer</h2>
    <div class="fs-4">
        Points: <?php echo $round['dealer']['score']; ?>
    </div>
    <hr>
    <div class="d-flex flex-wrap justify-content-center">
        @foreach ($round['dealer']['cards'] as $card)
            <span class="showcard fs-4 {{ $card['style'] }}"> {!! $card['show'] !!} </span>
        @endforeach
    </div>

    @if (!is_null($round['dealer']['result']))
        <hr>
        <div class="fs-4">
            Result: {{ $round['dealer']['result'] }}
        </div>
    @endif
</div>
