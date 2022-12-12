<div class="container-fluid bg-light">
    <div class="container">

        <nav class="navbar bg-light p-2 mb-4">
            <div class="fs-3 col-4">
                <span class="showcard fs-4 mx-0 red">A &#x2666</span>
                <span class="showcard fs-4 mx-0 black">J &#x2660</span>
                E2 BlackJack

            </div>
            <div class="col-4 text-center">
                @if (isset($player))
                    <div class="fs-4 fs-bold">{{ $player['name'] }}</div>
                    <div test="navbar-cash" class="">Cash Balance ${{ $player['cash'] }}</div>
                    @if ($player['multiplayer'] > 0)
                        <div test="navbar-multiplayer">A.I. aggression level:
                            {{ $player['ailevel'] > 2 ? 'MAX' : $player['ailevel'] }}</div>
                    @endif
                @endif
            </div>

            <div class="col-4 text-end">
                <a class="button btn btn-sm btn-danger mx-2" href="/">Start Over</a>
                <a class="button btn btn-sm  btn-info mx-1" href="/history">History</a>
                <a class="button btn btn-sm btn-info " href="/play">Continue</a>
            </div>
        </nav>

    </div>
</div>
