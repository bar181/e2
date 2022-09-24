<?php

echo "Week4 <br>";
/*

asort           sorts an array by its values
ksort           sorts an array by its keys
shuffle         randomly sorts an array
array_pop       will extract the last value from an array
array_shift     will extract the first value from an array
array_push      will add a value to the end of the array (which is equivalent to the square bracket notation showed above).
count           will return a count of all the elements in an array.
in_array        checks if a value exists in an array.
array_search    searches the array for a given value and returns the first corresponding key if successful.
array_slice     extracts a slice of the array
array_sum       calculates the sum of values in an array
array_rand      picks one or more random keys out of an array

*/

$moves = ['rock','paper', 'scissor'];

$randomNumber = rand(0,2);
$move =  $moves[$randomNumber];

//shuffle cards
// $cards =[1,2,3,4,5,6,7,8,9,10,11,12,13]; # card value 1-king
// var_dump($cards);
// echo "<br>";
// shuffle($cards);
// var_dump($cards);

// associate array
$coin_counts =[
    'pennies' => 100,
    'nickels' => 25,
    'dimes' => 100,
    'quarters' => 34,
    'half_dollars' => 0,
];
$coin_values =[
    'pennies' => 0.01,
    'nickels' => 0.05,
    'dimes' => 0.10,
    'quarters' => 0.25,
    'half_dollars' => 0.50,
];

// for each loop
// foreach ($coin_counts as $key_name => $value_var_name) {}

$total = 0;
foreach ($coin_counts as $coin => $count) {
    $total += $count * $coin_values[$coin];
}

// var_dump($total);

// multiple level array
$coins =[
    'pennies' => [100,0.01],
    'nickels' => [25,0.05],
    'dimes' => [100,0.1],
    'quarters' => [34,0.25],
];

$total = 0;
foreach ($coins as $coin => $info) {
    $total += $info[0] * $info[1];
}
// echo "<br><br>";
// var_dump($total);


// multiple level array - associative 
// (much longer array but easier to extract data)
$coins =[
    'penny' => [
        'count' =>100, 
        'value' =>0.01,
    ],
    'nickel' => [
        'count' =>25, 
        'value' =>0.05,
    ],
    'dime' => [
        'count' =>100, 
        'value' =>0.10,
    ],
    'quarter' => [
        'count' =>34, 
        'value' =>0.25,
    ],
];

$total = 0;
foreach ($coins as $coin => $info) {
    $total += $info['count'] * $info['value'];
}
// echo "<br><br>";
// var_dump($total);


$cards =[1,2,3,4,5,6,7,8,9,10,11,12,13,14]; # card value 1-king
shuffle($cards);

$playerCards = array_splice($cards, 0,  count($cards)/2);
$computerCards = $cards;

// echo "<br>playerCards<br>";
// var_dump($playerCards);

// echo "<br>computerCards<br>";
// var_dump($computerCards);

$playerDraw = array_pop($playerCards);
$computerDraw = array_pop($computerCards);

// echo "<br>playerDraw<br>";
// var_dump($playerDraw);

// echo "<br>computerDraw<br>";
// var_dump($computerDraw);


$coin = ['heads', 'tails'];

$randomNumber = rand(0,1);
$player1 = $coin[$randomNumber];
$randomNumber = rand(0,1);
$flip = $coin[$randomNumber];
// echo "<br>flip<br>";
// var_dump($flip);

// if($flip == $player1) {
//     echo "<br>Player 1<br>";
// } else {
//     echo "<br>Player 2<br>";
// }

$moves = ['rock', 'paper', 'scissors'];
$p1 = $moves[rand(0,2)];
$p2 = $moves[rand(0,2)];
$winner = 'tie';
if($p1 = 'rock' && $p2 == 'paper') {
    $winner = '1';
} elseif($p2 = 'rock' && $p1 == 'paper'){
    $winner = '2';
}
// continue for other options


$total = 0;
var_dump(($total <= 30 or $total > 50));