<?php

session_start();


# ------------- HELPER FUNCTIONS ------------

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

# create a blank round array
function blankRound()
{
    $ogRound = [
        'player' => [
            'cards' => [],
            'cardValues' => 0,
            'aces' => 0,
            'winlose' => "",
            'wager' => 0,
        ],
        'dealer' => [
            'cards' => [],
            'cardValues' => 0,
            'aces' => 0,
            'winlose' => "",
            'standValue' => 17,
        ],
        'ai' => [
            'cards' => [],
            'cardValues' => 0,
            'aces' => 0,
            'winlose' => "",
            'wager' => 0,
        ],
        'winner' => false,
        'hitstand' => null,
    ];

    return $ogRound ;
}


# SESSION INFLUENCES

# setup ------------

$setup = [
    'playerName' => "",
    'cash' => 0,
    'multiPlayer' => false,
    'wager' => 0,
    'round' => 0,
 ];

if (isset($_SESSION['setup'])) {
    $setup =  $_SESSION['setup'];
}


# page ------------

$page = "starting";
if (isset($_SESSION['page'])) {
    $page =  $_SESSION['page'];
}

echo "<br>pagepage --->" . $page;

$round = null;
if (isset($_SESSION['round'])) {
    $round =  $_SESSION['round'];
}

$deckKeys = null;
if (isset($_SESSION['deckKeys'])) {
    $deckKeys =  $_SESSION['deckKeys'];
}

# ------------- NEW ROUND ------------

# new round when wager made (bool)
$newDeal = ($setup['wager'] > 0) ? true : false;

# get original - unshuffled deck of cards
$ogDeck = createDeck();

# defaults
$endRound = false;


# shuffled deck of cards for every new round  (only need deck keys)
if ($newDeal && $page == "play") {
    echo "<br> <br> <br> <br> - # shuffled deck of cards for every ----<br> <br> <br> -------";
    $setup['round'] ++;


    # round set up
    $deckKeys = array_keys($ogDeck);
    shuffle($deckKeys);
    $round = blankRound();

    # adjust cash balances for new round
    $setup['cash'] -= $setup['wager'];
    $round['player']['wager'] = $setup['wager'];
    $setup['wager'] = 0;

    # player's flop card - remove card from deck array and track activity
    $cardkey = array_pop($deckKeys);
    $card = $ogDeck[$cardkey];
    $round['player']['cards'][] = $card;
    $round['player']['cardValues'] = $card['value'];
    if ($card['value'] == 11) {
        $round['player']['aces']++;
    }

    # dealer flop card
    $cardkey = array_pop($deckKeys);
    $card = $ogDeck[$cardkey];

    $round['dealer']['cards'][] = $card;
    $round['dealer']['cardValues'] = $card['value'];
    if ($card['value'] == 11) {
        $round['dealer']['aces']++;
    }

    # ai flop card
    if ($setup['multiPlayer']) {
        $cardkey = array_pop($deckKeys);
        $card = $ogDeck[$cardkey];

        $round['ai']['cards'][] = $card;
        $round['ai']['cardValues'] = $card['value'];
        if ($card['value'] == 11) {
            $round['ai']['aces']++;
        }
    }
}

# player hits

if (!$newDeal && $page =="play" && $round['hitstand'] == "hit") {
    # new player card
    $cardkey = array_pop($deckKeys);
    $card = $ogDeck[$cardkey];
    $round['player']['cards'][] = $card;
    if ($card['value'] == 11) {
        $round['player']['aces']++;
    }
    $score = $round['player']['cardValues'] + $card['value'];

    # check if player wins or loses automatically

    # edge case : player over 21 but has aces
    if ($score > 21 && $round['player']['aces'] > 0) {
        $score -= 10;
        $round['player']['aces'] --;
    }

    # end round: 21 or bust!
    if ($score == 21) {
        $round['player']['winlose'] = "Win";
        $endRound = true;
    }
    if ($score > 21) {
        $round['player']['winlose'] = "Bust";
        $endRound = true;
    }

    # update the player's round details
    $round['player']['cardValues'] = $score;
    $round['hitstand'] = null;
}


# player stand
if (!$newDeal && $page =="play" && $round['hitstand'] == "stand") {
    $endRound = true;
}



# end of round - AI plays
if ($endRound && $setup['multiPlayer']) {
    # default toggle player must hit (boolean default)
    $isHit = 1;

    # player continues to receive additional cards until a winner is decided or player stands
    while ($isHit < 8) {
        # local variable score (for readability only)
        $dealerValue = $round['dealer']['cardValues'];

        # local variable of card's point value (for readability)
        $score = $round['ai']['cardValues'];

        # edge case : dealer over 21 but has aces
        if ($score > 21 && $round['ai']['aces'] > 0) {
            # Ace is now worth 1 point
            $score -= 10;
            $round['ai']['aces'] --;
            $round['ai']['cardValues'] -= 10;
        }

        # stand or hit decision
        if ($score >= 15) {
            $isHit += 10;
        }

        if ($isHit < 8) {
            $cardkey = array_pop($deckKeys);
            $card = $ogDeck[$cardkey];
            $round['ai']['cards'][] = $card;
            $round['ai']['cardValues'] = $round['ai']['cardValues'] + $card['value'];
            if ($card['value'] == 11) {
                $round['ai']['aces']++;
            }
        }

        $isHit ++;
    }
}



# end of round - Dealer must play
if ($endRound && strlen($round['player']['winlose']) < 1) {
    # ----------- dealer game play -----------

    # dealer hits default is true - this will be changed for specific cases
    $isHit = 1;

    # Dealer's turn action - use rule max 7 cards
    while ($isHit < 8) {
        echo "<br> dealer " . $round['dealer']['cardValues'] . " dealer winlose:" . $round['dealer']['winlose'] .
                 ": player winlose :" . $round['player']['winlose'];

        # local variable of card's point value (for readability)
        $score = $round['dealer']['cardValues'];

        # edge case : dealer over 21 but has aces
        if ($score > 21 && $round['dealer']['aces'] > 0) {
            # Ace is now worth 1 point
            $score -= 10;
            $round['dealer']['aces'] --;
            $round['dealer']['cardValues'] -= 10;
        }

        # basic stand decision (end dealer turn if 17+ points include bust)
        if ($score >= $round['dealer']['standValue']) {
            $isHit += 10;
        }

        # dealer must hit
        if ($isHit < 8) {
            $cardkey = array_pop($deckKeys);
            $card = $ogDeck[$cardkey];
            $round['dealer']['cards'][] = $card;
            $round['dealer']['cardValues'] = $round['dealer']['cardValues'] + $card['value'];
            if ($card['value'] == 11) {
                $round['dealer']['aces']++;
            }
        }

        $isHit ++;
    }
}

# end of round - Find winner
function winRound($dealer, $player)
{
    $playerResult = "Tie";
    if ($dealer > $player || $dealer <= 21) {
        $playerResult = "Lose";
    }

    if ($dealer < $player || $dealer > 21) {
        $playerResult = "Win";
    }
    return $playerResult;
}


# end of round - Find if player wins
if ($endRound && strlen($round['player']['winlose']) < 1) {
    $dealer = $round['dealer']['cardValues'];
    $player = $round['player']['cardValues'];
    $round['player']['winlose'] = winRound($dealer, $player);
}


# end of round - Find if ai wins
if ($endRound) {
    $dealer = $round['dealer']['cardValues'];
    $player = $round['ai']['cardValues'];
    $round['ai']['winlose'] = winRound($dealer, $player);
    if ($round['ai']['cardValues'] > 21) {
        $round['ai']['winlose'] = "Bust";
    }
}



# end of round - Close the round and pay the winner
if ($endRound) {
    if ($round['player']['winlose'] == "Tie") {
        $setup['cash'] += $round['player']['wager'];
    }

    if ($round['player']['winlose'] == "Win") {
        $setup['cash'] += 2 * $round['player']['wager'];
    }
    $round['winner'] = true;
}



echo "<br>pagepage" . $page;


# update sessions
$_SESSION['setup'] = $setup;
$_SESSION['page'] = $page;
$_SESSION['round'] = $round;
$_SESSION['deckKeys'] = $deckKeys;


echo "<br><br>_SESSION:round <br> ";
var_dump($_SESSION['round']);



echo "<br><br>_SESSION: ";
var_dump($_SESSION);


require "index-view.php";