<?php 
# gereral blackjack rules: https://en.wikipedia.org/wiki/Blackjack
# suits icons css: https://hesweb.dev/files/e2p1-examples/war/

# ----------- set global variables and defaults -----------

# game play variables (constants)
/*
End of game when the player has lost everything or doubled their money

You may alter the game rules:

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

 $gameRules['shoeSize'] =  intval($gameRules['reshufflePercent'] * 52 * $gameRules['numberOfDecks']);

 $totalPlayerWins = 0;
 $totalDealerWins = 0;
 $totalTies = 0;



# logic for when to hit or stand
/*
standAverageCards: player stands at this value (cutoff) or more if dealer shows a 2,3,7,8,9
standGoodCards: stand cutoff if dealer shows 4,5,6
standBadCards: stand cutoff if dealer shows face card or ace
dealerStand: casino rules where dealer must hit on 16 or less (stand on soft 17)
*/
$aiLogic = [
    'standardStand' => 14, 
    'goodStand' => 11, 
    'goodCards' => [4,5,6], 
    'badStand' => 16, 
    'badCards' => [10,11], 
    'dealerStand' => 17,
];


// set player/dealer global variables and defaults
$playerCash = $gameRules['startingCash'] ;
$dealerCash = $gameRules['startingCash'] ;
$totalRounds = 0 ;
$finalWinner = null ;

 # array to store round details - this will be displayed in HTML
 $rounds = [];

 # set default round deails (imutable)
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
 # first digit/letter of card and values

 $cardNumbers = [];
 $cardValues = [];
 for ($i = 2; $i <= 10; $i++) {
    $cardNumbers[$i] = $i;
    $cardValues[$i] = $i;
 }
 array_push($cardNumbers,"J", "Q", "K", "A");
 array_push($cardValues, 10, 10, 10, 11);
 
 # card's suit
 # source for suits icons: https://hesweb.dev/files/e2p1-examples/war/
 $cardSuits = ["♦", "♥", "♣", "♠"];

// print_r($cardNumbers);
// echo "<br>";
// print_r($cardValues);

 # create master card deck - 13 cards * 4 suits (imutable) * number of decks
$ogDeck = [];
$counter = 0;
for ($i = 0; $i < $gameRules['numberOfDecks']; $i++) {
    foreach ($cardSuits as $keySuit => $cardSuit) {
    foreach ($cardNumbers as $keyNumber => $cardNumber) {
        
            $ogDeck[$counter]['show'] = $cardNumbers[$keyNumber] . $cardSuit;
            $ogDeck[$counter]['value'] = $cardValues[$keyNumber];
            $counter ++;
        }
    }
}

// var_dump($ogDeck);

$deck = [];



# ----------- round activity-----------
$playerCash += $gameRules['betAmount'];
$dealerCash -= $gameRules['betAmount'];
# game while loop ends when either player or dealer is broke
while ($playerCash > 0 && $dealerCash > 0) {

    // test for predetermined # rounds
// for ($ii = 0; $ii <= 25; $ii++) {

    # shuffle deck (if cards remaining < shoe or settings set to reshuffle every turn)
    if ((count($deck) < $gameRules['shoeSize']) || $gameRules['shuffleTurn']) {
        $deck = $ogDeck;
        shuffle($deck);
        // echo "<br>Everybody's shuffling ! ";
    }

    # set default player and dealer round details
    $round = $ogRound;

    # player's hold card
    $card = array_pop($deck);
    $round['player']['cards'][] = $card['show'];
    $round['player']['cardValues'] = $card['value'];
    if ($card['value'] == 11) {
        $round['player']['aces']++;
    }

    // dealer flop card
    $card = array_pop($deck);
    $round['dealer']['cards'][] = $card['show'];
    $round['dealer']['cardValues'] = $card['value'];
    if ($card['value'] == 11) {
        $round['dealer']['aces']++;
    }

    # ----------- player game play -----------

    # player must hit for 2nd card
    $isHit = true;

    // while loop - until a winner is decide or player stands
    while ($isHit && $round['winner'] == null) {
        # local variable score (for readability only)
        $score = $round['player']['cardValues'];

        # end round: player busts (over 21)
        if ($score > 21 && $round['player']['aces'] < 1) {
            $round['winner'] = "Dealer";
            // break;
            $isHit = false;
        }

        # end round: player has 21 !
        if ($score == 21) {
            $round['winner'] = "Player";
            // break;
            $isHit = false;
        }

        # edge case : player over 21 but has aces
        if ($score > 21 && $round['player']['aces'] > 0) {
            # Ace is now worth 1 point
            $score -= 10;
            $round['player']['aces'] --;
            $round['player']['cardValues'] -= 10;
        }

        # basic stand decision - stand based on dealer's cards
        # logic designed by Brad - influenced by odds/percentages in wikipedia article

        $standDecision = $aiLogic['standardStand'];
        if (in_array($round['dealer']['cardValues'], $aiLogic['badCards'])) {
            # you are in a bad position (dealer has good cards) - player stands at higher amount
            # e.g. if dealer shows an ace, then keep hitting until you have a 17 (default) or better
            $standDecision = $aiLogic['badStand'];
        }
        if (in_array($round['dealer']['cardValues'], $aiLogic['goodCards'])) {
            # you are in a good position (dealer has bad cards) - stand earlier
            $standDecision = $aiLogic['goodStand'];
        }
        if ($score >= $standDecision) {
            $isHit = false;
        }

        if ($isHit) {
            # add additional player card
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
        # local variable score (for readability only)
        $score = $round['dealer']['cardValues'];

        # end round: dealer busts (over 21)
        if ($score > 21 && $round['dealer']['aces'] < 1) {
            $round['winner'] = "Player";
            // break;
            $isHit = false;
        }

        # end round: dealer has 21 !
        if ($score == 21) {
            $round['winner'] = "Dealer";
            // break;
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

        if ($isHit) {
            # add additional dealer card
            $card = array_pop($deck);
            $round['dealer']['cards'][] = $card['show'];
            $round['dealer']['cardValues'] += $card['value'];
            if ($card['value'] == 11) {
                $round['dealer']['aces']++;
            }
        }
    }

    # ----------- end of round details -----------

    if ($round['winner'] == "Player") {
        $playerCash += $gameRules['betAmount'];
        $dealerCash -= $gameRules['betAmount'];
    } elseif ($round['winner'] == "Dealer") {
        $playerCash -= $gameRules['betAmount'];
        $dealerCash += $gameRules['betAmount'];
    } else {
        # no declared winner during round - now evaluate scores
        if ($round['player']['cardValues'] >  $round['dealer']['cardValues']) {
            # player has higher score - player wins bet
            $playerCash += $gameRules['betAmount'];
            $dealerCash -= $gameRules['betAmount'];
            $round['winner'] = "Player";
        } elseif ($round['player']['cardValues'] <  $round['dealer']['cardValues']) {
            # dealer has higher score - dealer wins bet
            $playerCash -= $gameRules['betAmount'];
            $dealerCash += $gameRules['betAmount'];
            $round['winner'] = "Dealer";
        } else {
            # tie - bets are returned
            $round['winner'] = "Tie";
        }
    }

    # update cash balance
    if ($round['winner'] == "Player") {
        $totalPlayerWins++;
    } elseif ($round['winner'] == "Dealer") {
        $totalDealerWins++;
    } else {
        $totalTies++;
    }
    
    $round['player']['endCash'] = $playerCash;
    $round['dealer']['endCash'] = $dealerCash;

    # round is over - save rounds details
    $rounds[] = $round;

    // echo "<br>Round: " . count($rounds) . " winner: " . $round['winner'];
    // echo " Cash: " . $playerCash . " Dealer" . $dealerCash;
    // echo " Scores: " . $round['player']['cardValues'] . " " . $round['dealer']['cardValues'];
} 

# end of game action
$totalRounds = count($rounds);
if($playerCash < 1) {
    $finalWinner = "Dealer" ; 
} else {
    $finalWinner = "Player" ; 
}


echo "<br>totalRounds: " . $totalRounds . "<br>";
echo "<br>finalWinner: " . $finalWinner . "<br>";
echo "<br>playerCash: " . $playerCash . "<br>";
echo "<br>dealerCash: " . $dealerCash . "<br>";
echo "<br>";
echo "<br>win percent: " . $totalPlayerWins . " " . number_format(($totalPlayerWins +  $totalTies)/ $totalRounds * 100,2) . "<br>";
echo "<br>dealer percent: " . $totalDealerWins . " " . number_format(($totalDealerWins +  $totalTies)/ $totalRounds * 100,2) . "<br>";
echo "<br>tie percent: " . number_format($totalTies / $totalRounds * 100) . "<br>";


//  echo "<pre>";
// print_r($rounds);
// echo "</pre>";


require "index-view.php";