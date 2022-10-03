<?php 
# reference sources - final player hit/stand logic created by Brad Ross with influence based on odds calculations provided in the wikipedia summary
# gereral blackjack rules: https://en.wikipedia.org/wiki/Blackjack
# suits icons css: https://hesweb.dev/files/e2p1-examples/war/
# table css: https://www.w3schools.com/css/tryit.asp?filename=trycss_table_fancy
# color theme: https://material.io/design/color/the-color-system.html#color-theme-creation


# ----------- set global variables and defaults -----------

/*
Configuarable game rules:
    Can change any of these values

    startingCash: (int) how much cash both the dealer and player have at the start
    betAmount: (int) wager placed for each hand (tie returns cash)
    numberOfDecks: (int) number of decks used 
    shuffleTurn: (bool) true means the deck gets shuffled after each round 
    reshufflePercent: (decimal) when does the deck automatically get shuffled (usually at 50% of deck size)
*/
$gameRules = [
    'startingCash' => 100, 
    'betAmount' => 10, 
    'numberOfDecks' => 6, 
    'shuffleTurn' => false, 
    'reshufflePercent' => 0.5, 
 ];

 # final shoe size calculation (result is number of cards remaining before shuffling the deck)
 $gameRules['shoeSize'] =  intval($gameRules['reshufflePercent'] * 52 * $gameRules['numberOfDecks']);

# logic for when to hit or stand - Configuarable
/*
    You may adjust the stand values for the player 
    Stand values for the dealer are based on casino rules

    standAverageCards: player stands at this value (cutoff) or more if dealer shows a 2, 3, 7, 8, 9
    standGoodCards: stand cutoff if dealer shows 4, 5, 6
    standBadCards: stand cutoff if dealer shows face card or ace
    dealerStand: casino rules where dealer must hit on 16 or less (stand on soft 17)
*/
$aiLogic = [
    'standardStand' => 14, 
    'goodStand' => 12, 
    'goodCards' => [4, 5, 6], 
    'badStand' => 16, 
    'badCards' => [10, 11], 
    'dealerStand' => 17, 
];

# set player/dealer global variables and defaults
$playerCash = $gameRules['startingCash'] ;
$dealerCash = $gameRules['startingCash'] ;
$totalRounds = 0 ;
$finalWinner = null ;

 # array to store round details - this will be displayed in HTML
 $rounds = [];

 # set default round details - some variables will be altered for each round
 $ogRound = [
    'round' => 0 , 
    'player' => [
        'cards' => [], 
        'cardValues' => 0, 
        'aces' => 0, 
        'endCash' => 0, 
    ], 
    'dealer' => [
        'cards' => [], 
        'cardValues' => 0, 
        'aces' => 0, 
        'endCash' => 0
    ], 
    'winner' => null, 
 ];


 # ----------- create deck of cards -----------
 
 # associative array key is first digit/letter of card and value is card's point values
 $cardValues = array("2"=>2, "3"=>3, "4"=>4, "5"=>5, "6"=>6, 7=>7, "8"=>8, 
 "9"=>9, "10"=>10, "J"=>10, "Q"=>10, "K"=>10, "A"=>11);

 # card's suit: diamonds, hearts, clubs, spades (see css source - top of page)
 $cardSuits = ["♦", "♥", "♣", "♠"];

 # create master card deck with all playing cards - 13 cards * 4 suits * number of decks 
$ogDeck = [];
$counter = 0;
for ($i = 0; $i < $gameRules['numberOfDecks']; $i++) {
    foreach ($cardSuits as $keySuit => $cardSuit) {

        # add each card display (i.e. Ace of Hearts) and the card's point value (i.e. 11)
        foreach ($cardValues as $card => $value) { 
            $ogDeck[$counter]['show'] = $card . $cardSuit;
            $ogDeck[$counter]['value'] = $value;
            $counter ++;
        }
    }
}

# default array holding actual deck of cards used for each round 
$deck = [];


# ----------- round activity-----------

# game loop continues until either player or dealer is broke
while ($playerCash > 0 && $dealerCash > 0) {

    # shuffle deck (if cards remaining < shoe or settings set to reshuffle every turn)
    if ((count($deck) < $gameRules['shoeSize']) || $gameRules['shuffleTurn']) {
        $deck = $ogDeck;
        shuffle($deck);
    }

    # set default player and dealer round details
    $round = $ogRound;

    # player's flop card - remove card from deck array and track activity
    $card = array_pop($deck);
    $round['player']['cards'][] = $card['show'];
    $round['player']['cardValues'] = $card['value'];
    if ($card['value'] == 11) {
        $round['player']['aces']++;
    }

    # dealer flop card - remove card from deck array and track activity
    $card = array_pop($deck);
    $round['dealer']['cards'][] = $card['show'];
    $round['dealer']['cardValues'] = $card['value'];
    if ($card['value'] == 11) {
        $round['dealer']['aces']++;
    }

    # ----------- player game play -----------

    # default toggle player must hit (boolean default)
    $isHit = true;

    # player continues to receive additional cards until a winner is decided or player stands
    while ($isHit && $round['winner'] == null) {
        # local variable score (for readability only)
        $score = $round['player']['cardValues'];

        # end round: player busts (over 21)
        if ($score > 21 && $round['player']['aces'] < 1) {
            $round['winner'] = "Dealer";
            $isHit = false;
        }

        # end round: player has 21 !
        if ($score == 21) {
            $round['winner'] = "Player";
            $isHit = false;
        }

        # edge case : player over 21 but has aces
        if ($score > 21 && $round['player']['aces'] > 0) {
            # Ace is now worth 1 point (Ace cannot be reused)
            $score -= 10;
            $round['player']['aces'] --;
            $round['player']['cardValues'] -= 10;
        }

        # basic stand decisions influenced by odds/percentages in wikipedia article
        # default minimum points in hand to stand when dealer shows an average card
        $standDecision = $aiLogic['standardStand'];

        if (in_array($round['dealer']['cardValues'], $aiLogic['badCards'])) {
            # player in a bad position (dealer has good cards like an Ace or Face card) - player stands at higher amount
            $standDecision = $aiLogic['badStand'];
        }
        if (in_array($round['dealer']['cardValues'], $aiLogic['goodCards'])) {
            # player in a good position (dealer has bad cards like a 4, 5 or 6) - stand earlier
            $standDecision = $aiLogic['goodStand'];
        }

        # identify if the player's card value is high enough to stand 
        if ($score >= $standDecision) {
            $isHit = false;
        }

        # player decides to hit
        if ($isHit) {
            # add additional card and track results
            $card = array_pop($deck);
            $round['player']['cards'][] = $card['show'];
            $round['player']['cardValues'] += $card['value'];
            if ($card['value'] == 11) {
                $round['player']['aces']++;
            }
        }
    }


    # ----------- dealer game play -----------

    # dealer hits until a winner selected or must stand
    $isHit = true;

    while ($isHit && $round['winner'] == null) {
        # local variable of card's point value (for readability)
        $score = $round['dealer']['cardValues'];

        # end round: dealer busts (over 21)
        if ($score > 21 && $round['dealer']['aces'] < 1) {
            $round['winner'] = "Player";
            $isHit = false;
        }

        # end round: dealer has 21 !
        if ($score == 21) {
            $round['winner'] = "Dealer";
            $isHit = false;
        }

        # edge case : dealer over 21 but has aces
        if ($score > 21 && $round['dealer']['aces'] > 0) {
            # Ace is now worth 1 point
            $score -= 10;
            $round['dealer']['aces'] --;
            $round['dealer']['cardValues'] -= 10;
        }

        # basic stand decision
        if ($score >= $aiLogic['dealerStand']) {
            $isHit = false;
        }

        # dealer must hit
        if ($isHit) {

            # add additional card
            $card = array_pop($deck);
            $round['dealer']['cards'][] = $card['show'];
            $round['dealer']['cardValues'] += $card['value'];
            if ($card['value'] == 11) {
                $round['dealer']['aces']++;
            }
        }
    }

    # ----------- end of round details -----------

    # a winner was already declared
    if ($round['winner'] == "Player") {
        $playerCash += $gameRules['betAmount'];
        $dealerCash -= $gameRules['betAmount'];
    } elseif ($round['winner'] == "Dealer") {
        $playerCash -= $gameRules['betAmount'];
        $dealerCash += $gameRules['betAmount'];
    } else {

        # no declared winner during round - evaluate scores
        if ($round['player']['cardValues'] >  $round['dealer']['cardValues']) {
  
            # player has higher score - player wins (adjust cash based on wager)
            $playerCash += $gameRules['betAmount'];
            $dealerCash -= $gameRules['betAmount'];
            $round['winner'] = "Player";
        } elseif ($round['player']['cardValues'] <  $round['dealer']['cardValues']) {

            # dealer has higher score - dealer wins
            $playerCash -= $gameRules['betAmount'];
            $dealerCash += $gameRules['betAmount'];
            $round['winner'] = "Dealer";
        } else {
 
            # tie - bets are returned
            $round['winner'] = "Tie";
        }
    }

    # track updated update cash balance
    $round['player']['endCash'] = $playerCash;
    $round['dealer']['endCash'] = $dealerCash;

    # round is over - save round details
    $rounds[] = $round;

  } 

# ----------- end of game action -----------

# identify total rounds and the winner
$totalRounds = count($rounds);
if($playerCash < 1) {
    $finalWinner = "Dealer" ; 
} else {
    $finalWinner = "Player" ; 
}


# ----------- display game on website -----------
require "index-view.php";