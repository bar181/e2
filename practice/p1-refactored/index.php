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
    'numberOfDecks' => 1,
    'shuffleTurn' => false,
    'reshufflePercent' => 0.5,
 ];

# final shoe size calculation (result is number of cards remaining before shuffling the deck)
$gameRules['shoeSize'] =  intval($gameRules['reshufflePercent'] * 52 * $gameRules['numberOfDecks']);

# logic for when to hit or stand - Configuarable
/*
    You may adjust the stand values for the player
    Stand values for the dealer are based on casino rules *use caution if changing dealer stand value

    standardStand: (int) player stands at this value (cutoff) or more if dealer shows an average card (2, 3, 7, 8, 9)
    goodStand: (int) stand cutoff if dealer shows cards that are good for the player
    goodCards: (array of int) array of good cards for the player (i.e. dealer shows 4,5,6)
    badStand: (int) stand cutoff if dealer shows face card or ace
    badCards: (array of int) array of bad cards for the player (i.e. dealer shows a 10 or 11 point card)
    dealerStand: (int) casino rules where dealer must hit on 16 or less (stand on soft 17)
*/

$aiLogic = [
        'standardStand' => 14,
        'goodStand' => 12,
        'goodCards' => [4, 5, 6],
        'badStand' => 16,
        'badCards' => [10, 11],
        'dealerStand' => 17,
    ];






# running cash balances
$playerCash = $gameRules['startingCash'] ;
$dealerCash = $gameRules['startingCash'] ;

# array of round details - this will be displayed in HTML
$rounds = [];

# set default round details - some variables will be altered for each round
// 'cards' => ['style' => "",'show' => "",'value' => "",],
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

function createDeck($decks = 1)
{
    $cardValues = array("2"=>2, "3"=>3, "4"=>4, "5"=>5, "6"=>6, 7=>7, "8"=>8,
    "9"=>9, "10"=>10, "J"=>10, "Q"=>10, "K"=>10, "A"=>11);
    $cardSuits = ["diamond"=>"♦", "heart"=>"♥", "club"=>"♣", "spade"=>"♠"];
    $ogDeck = [];
    $counter = 0;

    for ($i = 0; $i < $decks; $i++) {
        foreach ($cardSuits as $keySuit => $cardSuit) {
            $style = (in_array($keySuit, ["diamond", "heart"])) ? 'red' : 'black';

            foreach ($cardValues as $card => $value) {
                $ogDeck[$counter]['show'] = $card . $cardSuit;
                $ogDeck[$counter]['value'] = $value;
                $ogDeck[$counter]['style'] = $style;
                $counter ++;
            }
        }
    }
    shuffle($ogDeck);
    return $ogDeck;
}

# ----------- logic if the player hits or stands -----------

function standDecision($aiLogic, $cardValue)
{
    $standDecision = $aiLogic['standardStand'];

    if (in_array($cardValue, $aiLogic['badCards'])) {
        # player in a bad position (dealer has good cards like an Ace or Face card) - player stands at higher amount
        $standDecision = $aiLogic['badStand'];
    }
    if (in_array($cardValue, $aiLogic['goodCards'])) {
        # player in a good position (dealer has bad cards like a 4, 5 or 6) - stand earlier
        $standDecision = $aiLogic['goodStand'];
    }

    return $standDecision;
}



# ----------- round activity-----------
$deck = createDeck($gameRules['numberOfDecks']);

# game loop continues until either the player or the dealer is out of cash
while ($playerCash > 0 && $dealerCash > 0) {
    # shuffle a new deck (if cards remaining is less than shoe OR the game options are set to reshuffle every turn)
    if ((count($deck) < $gameRules['shoeSize']) || $gameRules['shuffleTurn']) {
        $deck = createDeck($gameRules['numberOfDecks']);
    }

    # set default round details
    $round = $ogRound;

    # player's flop card - remove card from deck array and track activity, made points adjustments for Aces
    $card = array_pop($deck);
    $round['player']['cards'][] = $card;
    $round['player']['cardValues'] = $card['value'];
    if ($card['value'] == 11) {
        $round['player']['aces']++;
    }

    # dealer flop card - remove card from deck array and track activity
    $card = array_pop($deck);
    $round['dealer']['cards'][] = $card;
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
        $dealerValue = $round['dealer']['cardValues'];
        $score = $round['player']['cardValues'];

        # edge case : player over 21 but has aces
        if ($score > 21 && $round['player']['aces'] > 0) {
            $score -= 10;
            $round['player']['aces'] --;
            $round['player']['cardValues'] -= 10;
        }

        # end round: 21 or bust!
        if ($score == 21) {
            $round['winner'] = "Player";
            $isHit = false;
        }
        if ($score > 21) {
            $round['winner'] = "Dealer";
            $isHit = false;
        }


        # identify if the player's card value is high enough to stand
        if ($score >= standDecision($aiLogic, $dealerValue)) {
            $isHit = false;
        }

        # player decides to hit
        if ($isHit) {
            # add additional card and track results
            $card = array_pop($deck);
            $round['player']['cards'][] = $card;

            $round['player']['cardValues'] += $card['value'];
            if ($card['value'] == 11) {
                $round['player']['aces']++;
            }
        }
    }


    # ----------- dealer game play -----------

    # dealer hits default is true - this will be changed for specific cases
    $isHit = true;

    # Dealer's turn action
    while ($isHit && $round['winner'] == null) {
        # local variable of card's point value (for readability)
        $score = $round['dealer']['cardValues'];


        # edge case : dealer over 21 but has aces
        if ($score > 21 && $round['dealer']['aces'] > 0) {
            # Ace is now worth 1 point
            $score -= 10;
            $round['dealer']['aces'] --;
            $round['dealer']['cardValues'] -= 10;
        }

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

        # basic stand decision
        if ($score >= $aiLogic['dealerStand']) {
            $isHit = false;
        }

        # dealer must hit
        if ($isHit) {
            # add additional card
            $card = array_pop($deck);
            $round['dealer']['cards'][] = $card;
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
if ($playerCash < 1) {
    $finalWinner = "Dealer" ;
} else {
    $finalWinner = "Player" ;
}


# ----------- display game on website -----------
require "index-view.php";