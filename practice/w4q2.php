<?php

$cards =[1,2,3,4,5,6,7,8,9,10]; # card value 1-10
shuffle($cards);

$playerCards = [];
$computerCards = [];

# code assign cards to player then computer

// echo "<br> Approach 1: foreach<br>";

$dealPlayer1 = true;    # bool: do you deal to player1 ?
foreach ($cards as $key => $card) {

    if($dealPlayer1) {
        $playerCards[] = $card;
    } else {
        $computerCards[] = $card;
    }
    $dealPlayer1 =!$dealPlayer1; # toggle 
}

# verify - both should have 5 random cards
echo "<br><br>1: foreach loop<br>";
echo "<br> playerCards<br>";
var_dump($playerCards);
echo "<br> computerCards<br>";
var_dump($computerCards);

# ---------------------------------

// echo "<br> Approach 2: for loop with array_push<br>";
# reset values
$cards =[1,2,3,4,5,6,7,8,9,10]; # card value 1-10
shuffle($cards);
$playerCards = [];
$computerCards = [];

$dealPlayer1 = true;
for ($i = 0; $i < count($cards); $i++) {

    if($dealPlayer1) {
        array_push($playerCards, $cards[$i]);
    } else {
        array_push($computerCards, $cards[$i]);
    }
    $dealPlayer1 =!$dealPlayer1;    # toggle
}

# verify - both should have 5 random cards
echo "<br><br>2: for loop<br>";
echo "<br> playerCards<br>";
var_dump($playerCards);
echo "<br> computerCards<br>";
var_dump($computerCards);


// echo "<br> Approach 3: while loop with array_pop<br>";
# reset values
$cards =[1,2,3,4,5,6,7,8,9,10]; # card value 1-10
shuffle($cards);
$playerCards = [];
$computerCards = [];

$dealPlayer1 = true;
while($cards) {
    if($dealPlayer1) {
        $playerCards[] = array_pop($cards);
    } else {
        $computerCards[] = array_pop($cards);
    }
    $dealPlayer1 =!$dealPlayer1;    # toggle
}

# verify - both should have 5 random cards
echo "<br><br>3: while loop<br>";
echo "<br> playerCards<br>";
var_dump($playerCards);
echo "<br> computerCards<br>";
var_dump($computerCards);