<!doctype html>
<html lang='en'>

<head>
    <title>E2 Project 2 by Bradley Ross</title>
    <meta charset='utf-8'>
    <link href=data:, rel=icon>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">


</head>

<body>
    <div class="container">
        <nav class="navbar bg-light p-2 mb-4">
            <div class="fs-2">
                E2 BlackJack
            </div>
            <div class="fs-2">
                <?php echo $setup['playerName']; ?> Cash $<?php echo $setup['cash']; ?>
            </div>

            <div>
                <form method='GET' action='process.php'>
                    <input type="hidden" name="quit" id="quit">
                    <button type='submit' class="btn btn-danger">Quit ;(</button>
                </form>
            </div>
        </nav>

        <div id="startingDiv" class="<?php echo ($page == "starting") ? 'show' : 'hide' ?>">
            <form method='POST' action='process.php'>

                <div class="d-flex">

                    <div class="col-6 m-2">
                        <img src="dogs_poker.jpg" class="card-img-top" alt="A painting by Brad ">
                        <div class="mt-5 text-center">
                            <h2>E2 Project 2</h2>
                            <h2>by Bradley Ross</h2>
                        </div>
                    </div>

                    <div class="col-6 card m-2 p-3">

                        <div class="my-4">
                            <label for="playerName" class="form-label">Your name</label>
                            <input type="text" class="form-control" name="playerName" id="playerName"
                                placeholder="Your name" value="Brad">
                        </div>
                        <div class="mb-4">
                            <label for="cash" class="form-label">Starting Balance</label>
                            <input type="text" class="form-control" name="cash" id="cash" value="100">
                        </div>
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="multiPlayer" name="multiPlayer"
                                    checked>
                                <label class="form-check-label" for="multiPlayer">Multi Player Game</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <input type="hidden" name="setup" id="setup">
                            <button type='submit' class="btn btn-primary mb-3">Get Started</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <div id="newRoundDiv" class="<?php echo ($page == "newRound") ? 'show' : 'hide' ?>">

            <form method='POST' action='process.php'>

                <div class="d-flex">


                    <div class="col-6 card m-1">

                        <img src="poker_table.jpg" class="card-img-top" alt="A painting by Brad ">
                        <div class="my-4 text-center">
                            <h2>Let's Deal !</h2>
                        </div>

                    </div>

                    <div class="col card m-1 p-2 text-center">

                        <div class="my-5">
                            <h2>Cash remaining</h2>
                            <h2>$<?php echo $setup['cash']; ?> </h2>
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
                                    <?php echo ($setup['cash'] >=50) ? '' : 'disabled'; ?>>
                                <label class="form-check-label" for="w50">
                                    $50
                                </label>
                            </div>
                        </div>


                        <div class="d-flex justify-content-center">
                            <input type="hidden" name="newRound" id="newRound">
                            <button type='submit' class="btn btn-primary mb-3">Deal</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>


        <div id="playDiv" class="<?php echo ($page == "play") ? 'show' : 'hide' ?>">
            <form method='POST' action='process.php'>
                <div class="d-flex">

                    <div class="col card m-1 p-2">
                        <h2>Dealer</h2>
                        <div class="fs-2">
                            Points:
                        </div>
                        <hr>
                        <div class="d-flex flex-wrap">
                            <span class="showcard red">K ♥</span>
                        </div>
                    </div>

                    <div class="col-6 card m-1 p-2">
                        <h2><?php echo $setup['playerName'];?></h2>
                        <div class="fs-2">
                            Points:
                        </div>
                        <hr>
                        <div class="d-flex flex-wrap">
                            <span class="showcard black">J ♠</span>
                            <span class="showcard red">10 ♥</span>
                        </div>
                        <div class="my-4 d-flex justify-content-center">
                            <div class="form-check px-3">
                                <input class="form-check-input" type="radio" name="hitstand" id="stand" checked>
                                <label class="form-check-label" for="stand">
                                    Stand
                                </label>
                            </div>
                            <div class="form-check px-3">
                                <input class="form-check-input" type="radio" name="hitstand" id="hit">
                                <label class="form-check-label" for="hit">
                                    Hit
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <input type="hidden" name="play" id="play">
                            <button type='submit' class="btn btn-primary mb-3">Play !</button>
                        </div>
                    </div>

                    <div class="col card m-1 p-2 <?php echo ($setup['multiPlayer']) ? 'show' : 'hide'; ?>">
                        <h2>AI Player</h2>
                        <div class="fs-2">
                            Points:
                        </div>
                        <hr>
                        <div class="d-flex flex-wrap">
                            <span class="showcard black">5 ♠</span>
                            <span class="showcard black">J ♠</span>
                            <span class="showcard red">10 ♥</span>
                            <span class="showcard black">J ♠</span>
                            <span class="showcard red">10 ♥</span>
                        </div>
                    </div>

                </div>
            </form>
        </div>


    </div>
</body>

</html>