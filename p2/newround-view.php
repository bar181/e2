<div class="col-6 card m-1 py-2 px-5">
    <h2 class="mt-5">Lifetime Stats</h2>
    <h4>Rounds Played: <?php echo $stats['rounds'];?></h4>
    <h4>Wins: <?php echo $stats['wins'];?></h4>
    <h4>Loses: <?php echo $stats['loses'];?></h4>
    <h4>Ties: <?php echo $stats['ties'];?></h4>
    <h4><strong>Blackjacks: <?php echo $stats['blackjacks'];?></strong></h4>
</div>

<div class="col card m-1 p-2 text-center">

    <div class="<?php echo ($setup["cash"] < 10) ? "hide" : "show"; ?>">
        <form method="POST" action="process.php">

            <div class="my-5">
                <h2>Cash remaining</h2>
                <h2>$<?php echo $setup["cash"]; ?> </h2>
            </div>

            <div class="">
                <h2>Wager this Round</h2>
            </div>

            <div class="my-2 d-flex justify-content-center">
                <div class="form-check px-3">
                    <input class="form-check-input" type="radio" name="wager" id="w10" value="w10" checked>
                    <label class="form-check-label" for="w10">
                        $10
                    </label>
                </div>
                <div class="form-check px-3 ">
                    <input class="form-check-input" type="radio" name="wager" id="w50" value="w50"
                        <?php echo ($setup["cash"] >=50) ? "" : "disabled"; ?>>
                    <label class="form-check-label" for="w50">
                        $50
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <input type="hidden" name="newRound" id="newRound">
                <button type="submit" class="btn btn-primary mb-3">Deal</button>
            </div>
        </form>
    </div>

    <div class="<?php echo ($setup["cash"] < 10) ? "show" : "hide"; ?>">
        <h1 class="py-5"> Out of Money :(</h1>
        <form method="GET" action="process.php">
            <input type="hidden" name="quit" id="quit">
            <button type="submit" class="btn btn-danger">Quit ;(</button>
        </form>
    </div>

</div>