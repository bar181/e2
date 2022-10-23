<!doctype html>
<html lang="en">

<head>
    <title>E2 Project 2 by Bradley Ross</title>
    <meta charset="utf-8">
    <link href=data:, rel=icon>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php require "navbar-view.php"; ?>

    <div class="container">
        <?php require "instructions-view.php"; ?>
        <div class="d-flex">
            <?php ($page == 'starting') ? require "setup-view.php" : "";  ?>
            <?php ($page == 'newRound') ? require "newround-view.php" : ""; ?>
            <?php ($page == 'play') ? require "play-view.php" : ""; ?>
        </div>
    </div>
</body>

</html>