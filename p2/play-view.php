<div class="col card m-1 p-2">
    <h2>Dealer</h2>
    <div class="fs-2">
        Points: <?php echo $round["dealer"]["cardValues"]; ?>
    </div>
    <hr>
    <div class="d-flex flex-wrap">

        <?php foreach ($round["dealer"]["cards"] as $card) { ?>
        <span class="showcard fs-4 <?php echo $card['style']; ?>"><?php echo $card["show"]; ?></span>
        <?php } ?>
    </div>
</div>

<div class="col-6 card m-1 p-2">
    <h2><?php echo $setup["playerName"];?></h2>
    <div class="fs-2">
        Points: <?php echo $round["player"]["cardValues"];?>
    </div>
    <hr>
    <div class="d-flex flex-wrap">
        <?php foreach ($round["player"]["cards"] as $card) { ?>
        <span class="showcard fs-4 <?php echo $card['style']; ?>"><?php echo $card["show"]; ?></span>
        <?php } ?>
    </div>
    <hr>
    <div class="my-4 card bggreen <?php echo ($round['winner']) ? 'hide' : 'show' ?>">
        <form method="POST" action="process.php">
            <div class="my-4 d-flex justify-content-around fs-3">
                <div class="form-check px-3">
                    <input class="form-check-input" type="radio" name="hitstand" id="stand" value="stand"
                        <?php echo ($round["player"]["cardValues"] > 15) ? "checked" : ""; ?>>
                    <label class="form-check-label" for="stand">
                        Stand
                    </label>
                </div>
                <div class="form-check px-3">
                    <input class="form-check-input" type="radio" name="hitstand" id="hit" value="hit"
                        <?php echo ($round["player"]["cardValues"] <= 15) ? "checked" : ""; ?>>
                    <label class="form-check-label" for="hit">
                        Hit
                    </label>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <input type="hidden" name="play" id="play">
                <button type="submit" class="btn btn-primary mb-3 fs-1">Play !</button>
            </div>
        </form>
    </div>

    <div class="my-4 card bggreen <?php echo ($round['winner']) ? 'show' : 'hide' ?>">
        <div class="my-4 d-flex justify-content-around fs-3">
            <h1><?php echo $round["player"]["winlose"] ;?></h1>
        </div>
        <div class="d-flex justify-content-center">
            <form method="POST" action="process.php">
                <input type="hidden" name="endRound" id="endRound">
                <button type="submit" class="btn btn-primary mb-3 fs-1">Next Round</button>
            </form>
        </div>
    </div>

</div>

<div class="col card m-1 p-2 <?php echo ($setup["multiPlayer"]) ? "show" : "hide"; ?>">
    <h2>AI Player</h2>
    <div class="fs-2">
        Points: <?php echo $round["ai"]["cardValues"];?>
    </div>
    <hr>
    <div class="d-flex flex-wrap">
        <?php foreach ($round["ai"]["cards"] as $card) { ?>
        <span class="showcard fs-4 <?php echo $card['style']; ?>"><?php echo $card["show"]; ?></span>
        <?php } ?>
    </div>


    <div class="text-center <?php echo ($round['winner']) ? 'show' : 'hide' ?>">
        <hr>
        <h1 class="pt-4"><?php echo $round["ai"]["winlose"] ;?></h1>
    </div>

</div>