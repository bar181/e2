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
        <li>Wagers are returned if the dealer and player tie</li>
        <li>Card point values: numbered cards are worth their number, face cards are 10 points, aces can be either 1 or
            11</li>
        <li>The game ends when the player either doubles their money or goes broke</li>
        <li>$10 wagers each round and $100 starting cash</li>
        <li>No card splits. 21 pays standard return. Round winner declared on 21 points.</li>
        <li>6 card decks are used and reshuffled when 50% of cards are played (shoe size)</li>
        <li>Dealer hits on 16 including a soft 16 (ace and five) and stands at 17 or more</li>
    </ul>

    <h2>Mechanics</h2>
    <ul>
        <li>This is a two player blackjack simulation. One will be a "player" and the other a "dealer" for the entire
            game</li>
        <li>The Player places a wager for each round; this wager is matched by the dealer</li>
        <li>For each round the player and dealer are dealt a single card each</li>
        <li>For the player's turn, the player may receive additional cards ("hit") or stop receiving cards ("stand")
        </li>
        <li>The player will "hit" or "stand" using predefined logic</li>
        <li>For the player's turn, the player's turn is over when the point value of their cards is
            21 (win automatically), exceeds 21 (lose automatically) or the player stands</li>
        <li>For the dealer's turn, they must hit or stand according to house rules; the round is over if the dealer's
            points are 21 or more</li>
        <li>The person with the highest total card value wins wagers, ties returns original wagers</li>
        <li>The card deck is shuffled at predetermined times depending on house rules (e.g. shuffle
            when 50% of cards are shown)</li>
        <li>Rounds continue until one person has all the money and determined the game winner</li>
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
            <td><?php echo $key + 1; ?>
            </td>
            <td>
                <?php foreach ($round['player']['cards'] as $card) { ?>
                <span class='card'><?php echo $card; ?></span>
                <?php } ?>
            </td>
            <td><?php echo $round['player']['cardValues']; ?></td>
            <td>
                <?php foreach ($round['dealer']['cards'] as $card) { ?>
                <span class='card'><?php echo $card; ?></span>
                <?php } ?>
            </td>
            <td><?php echo $round['dealer']['cardValues']; ?></td>
            <td><strong><?php echo $round['winner']; ?></strong></td>
            <td>$<?php echo $round['player']['endCash']; ?></td>
            <td>$<?php echo $round['dealer']['endCash']; ?></td>
        </tr>
        <?php } ?>

    </table>
</body>

</html>