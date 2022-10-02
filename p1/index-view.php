<!doctype html>
<html lang='en'>

<head>
    <title>E2 Project 1 by Bradley Ross</title>
    <meta charset='utf-8'>
    <link href=data:, rel=icon>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Black Jack Simulator</h1>

    <h2>E2 Project 1 by Bradley Ross</h2>

    <h2>House Rules of the Game</h2>
    <ul>
        <li>A player wins a round and the wagers if they have card values higher that a dealer without exceeding 21
            points</li>
        <li>Wages are returned if the dealer and player tie</li>
        <li>Card values: numbered cards are worth their number, face cards are 10 points, aces can be either 1 or 11
        </li>
        <li>The game ends when the player either doubles their money or goes broke</li>
        <li>The house rules are set using $10 wagers and $100 starting cash</li>
        <li>6 card decks are used and reshuffled when 50% of cards area shown (shoe size)</li>
        <li>Dealer hits on 16 including a soft 16 (ace and five) and stands at 17 or more</li>
    </ul>

    <h2>Mechanics</h2>
    <ul>
        <li>The Player places a wager for each round and matched by the dealer</li>
        <li>For each round a player and dealer are dealt a single card</li>
        <li>For the player's turn, a player may receive additional cards (hit) until the point value is 21 (win
            automatically) or exceed 21 (lose automatically) or stops (stand)</li>
        <li>The player will hit or stand using logic by knowking their score and strength of the dealer's hand (i.e.
            player will stand earlier if the dealer shows a bad card)</li>
        <li>For the dealer's turn, they must hit or stand according to house rules</li>
        <li>Highest card values wins wagers, ties returns original wagers, over 21 automatically loses</li>
        <li>Rounds continue one person has all the money</li>
        <li>The deck is shuffled at predetermined times depending on house rules that can be configured (e.g. shuffle
            when 50% of cards are shown)</li>
    </ul>


    <h2>Results</h2>
    <ul>
        <li><strong>Rounds played: <?php echo $totalRounds; ?></strong> </li>
        <li><strong>Winner: <?php echo $finalWinner; ?></strong></li>
    </ul>

    <h2>Rounds</h2>
    <table id="table">

        <tr>
            <th>Round</th>
            <th>Player Cards</th>
            <th>Player Value</th>
            <th>Dealer Cards </th>
            <th>Dealer Value </th>
            <th>Winner</th>
            <th>Player Cash</th>
            <th>Dealer Cash</th>
        </tr>
        <?php foreach ($rounds as $key =>$round) { ?>
        <tr>
            <td><?php echo $key + 1 ?></td>
            <td>
                <?php foreach ($round['player']['cards'] as $card) { ?>
                <span class='card'><?php echo $card; ?></span>
                <?php } ?>
            </td>
            <td><?php echo $round['player']['cardValues'] ?></td>
            <td>
                <?php foreach ($round['dealer']['cards'] as $card) { ?>
                <span class='card'><?php echo $card; ?></span>
                <?php } ?>
            </td>
            <td><?php echo $round['dealer']['cardValues'] ?></td>
            <td><strong><?php echo $round['winner'] ?></strong></td>
            <td>$<?php echo $round['player']['endCash'] ?></td>
            <td>$<?php echo $round['dealer']['endCash'] ?></td>
        </tr>
        <?php } ?>
    </table>


</body>

</html>