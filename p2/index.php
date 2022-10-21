<?php

session_start();

# SESSION INFLUENCES

# ------------- SETUP UP DETAILS ------------

$setup = [
    'playerName' => "",
    'cash' => 0,
    'multiPlayer' => false,
    'wager' => 0,
    'round' => 1,
 ];

if (isset($_SESSION['setup'])) {
    $setup =  $_SESSION['setup'];
}


# ------------- IDENTIFY PAGE ------------

$page = "starting";
if (isset($_SESSION['page'])) {
    $page =  $_SESSION['page'];
}


# ------------- ROUND DETAILS ------------

$ogRound = [
   'player' => [
       'cards' => [],
       'cardValues' => 0,
       'winlose' => "",
   ],
   'dealer' => [
       'cards' => [],
       'cardValues' => 0,
       'winlose' => "",
   ],
   'ai' => [
       'cards' => [],
       'cardValues' => 0,
       'winlose' => "",
   ],
   'winner' => null,
   'deckKeys' => null,
];

if (isset($_SESSION['round'])) {
    $round =  $_SESSION['round'];
}

if (isset($_SESSION['deckKeys'])) {
    $deckKeys =  $_SESSION['deckKeys'];
}




# ------------- NEW ROUND ------------


echo "<br><br>setup: ";
var_dump($setup);


# new round when wager made (bool)
$newDeal = ($setup['wager'] > 0) ? true : false;

# adjust cash balance
$setup['cash'] -= $setup['wager'];
$setup['wager'] = 0;






# defaults

# deck of cards in order (key 0 = 2♦ ... key 51 = A♠)
function createDeck()
{
    $cardValues = array("2"=>2, "3"=>3, "4"=>4, "5"=>5, "6"=>6, 7=>7, "8"=>8,
    "9"=>9, "10"=>10, "J"=>10, "Q"=>10, "K"=>10, "A"=>11);
    $cardSuits = ["diamond"=>"♦", "heart"=>"♥", "club"=>"♣", "spade"=>"♠"];
    $ogDeck = [];
    $counter = 0;

    foreach ($cardSuits as $keySuit => $cardSuit) {
        $style = (in_array($keySuit, ["diamond", "heart"])) ? 'red' : 'black';

        foreach ($cardValues as $card => $value) {
            $ogDeck[$counter]['show'] = $card . $cardSuit;
            $ogDeck[$counter]['value'] = $value;
            $ogDeck[$counter]['style'] = $style;
            $counter ++;
        }
    }

    return $ogDeck;
}

# get original - unshuffled deck of cards
$ogDeck = createDeck();

# shuffled deck of cards for every new round  (only need deck keys)
if ($newDeal) {
    $deckKeys = array_keys($ogDeck);
    shuffle($deckKeys);
    $round = $ogRound;
}


echo "<br><br>newDeal: ";
var_dump($newDeal);



echo "<br><br>deck: ";
var_dump($deck);


echo "<br><br>_SESSION: ";
var_dump($_SESSION);


# update sessions
$_SESSION['setup'] = $setup;
$_SESSION['page'] = $page;
$_SESSION['deckKeys'] = $deckKeys;
$_SESSION['round'] = $round;



require "index-view.php";