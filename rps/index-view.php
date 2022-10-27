<!doctype html>
<html lang="en">

<head>
    <title>E2 Project 2 by Bradley Ross</title>
    <meta charset="utf-8">
    <link href=data:, rel=icon>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1>Rock Paper Scissor - Week 9</h1>
        <h2>Total Wins: <?php echo $wins;?>, Ties: <?php echo $ties;?>, Loses: <?php echo $loses;?></h2>
        <h2 class="pb-3">Total Rounds: <strong><?php echo $totalRounds;?> </strong></h2>
        <?php foreach ($results as $key=>$result) { ?>
        <div class="card m-3 p-2">
            Round <?php echo($key + 1); ?><br>
            Player: <?php echo $result["player"]; ?>,
            Computer: <?php echo $result["computer"];?><br>
            <strong><?php echo $result["outcome"];?> </strong>
        </div>
        <?php } ?>
    </div>
</body>

</html>