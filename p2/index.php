<?php

session_start();

# ------------- HELPER FUNCTIONS ------------

# deck of cards in order (key 0 = 2♦ ... key 51 = A♠)
function createDeck()
{
    $cardValues = array("2"=>2, "3"=>3, "4"=>4, "5"=>5, "6"=>6, 7=>7, "8"=>8,
    "9"=>9, "10"=>10, "J"=>10, "Q"=>10, "K"=>10, "A"=>11);
    # https://en.wikipedia.org/wiki/Playing_cards_in_Unicode

    $cardSuits = ["diamond"=>"&#x2666", "heart"=>"&#x2665", "club"=>"&#x2663", "spade"=>"&#x2660"];
    $ogDeck = [];
    $counter = 0;

    foreach ($cardSuits as $keySuit => $cardSuit) {
        $style = (in_array($keySuit, ["diamond", "heart"])) ? "red" : "black";

        foreach ($cardValues as $card => $value) {
            $ogDeck[$counter]["show"] = $card . $cardSuit;
            $ogDeck[$counter]["value"] = $value;
            $ogDeck[$counter]["style"] = $style;
            $counter ++;
        }
    }

    return $ogDeck;
}

# create a blank round array
function blankRound()
{
    $ogRound = [
        "player" => [
            "cards" => [],
            "cardValues" => 0,
            "aces" => 0,
            "winlose" => "",
            "wager" => 0,
        ],
        "dealer" => [
            "cards" => [],
            "cardValues" => 0,
            "aces" => 0,
            "winlose" => "",
            "standValue" => 17,
        ],
        "ai" => [
            "cards" => [],
            "cardValues" => 0,
            "aces" => 0,
            "winlose" => "",
            "wager" => 0,
        ],
        "winner" => false,
        "hitstand" => null,
    ];

    return $ogRound ;
}


# end of round - Find winner (based on card points for the dealer vs a player)
function winRound($dealerPoints, $playerPoints)
{
    $playerResult = "Tie";

    # dealer wins
    if ($dealerPoints > $playerPoints && $dealerPoints <= 21) {
        $playerResult = "Lose";
    }

    # dealer is bust or player has more points
    if ($dealerPoints < $playerPoints || $dealerPoints > 21) {
        $playerResult = "Win";
    }

    return $playerResult;
}


# ------------- SESSION INFLUENCES ------------

$setup = [
    "playerName" => "",
    "cash" => 0,
    "multiPlayer" => false,
    "wager" => 0,
    "round" => 0,
 ];

if (isset($_SESSION["setup"])) {
    $setup =  $_SESSION["setup"];
}

$stats = [
    "rounds" => 0,
    "wins" => 0,
    "ties" => 0,
    "loses" => 0,
    "blackjacks" => 0,
 ];

if (isset($_SESSION["stats"])) {
    $stats =  $_SESSION["stats"];
}

$page = "starting";
if (isset($_SESSION["page"])) {
    $page =  $_SESSION["page"];
}

$round = null;
if (isset($_SESSION["round"])) {
    $round =  $_SESSION["round"];
}

$deckKeys = null;
if (isset($_SESSION["deckKeys"])) {
    $deckKeys =  $_SESSION["deckKeys"];
}


# ------------- NEW ROUND ------------

# new round when wager made (bool)
$newDeal = ($setup["wager"] > 0) ? true : false;

# get original - unshuffled deck of cards
$ogDeck = createDeck();

# defaults
$endRound = false;


# new round actions
# shuffle deck, update cash based on wager and deal first card to each player
if ($newDeal && $page == "play") {
    $setup["round"] ++;

    # round set up
    $deckKeys = array_keys($ogDeck);
    shuffle($deckKeys);
    $round = blankRound();

    # adjust cash balances for new round
    $setup["cash"] -= $setup["wager"];
    $round["player"]["wager"] = $setup["wager"];
    $setup["wager"] = 0;

    # player"s flop card - remove card from deck array and track activity
    $cardkey = array_pop($deckKeys);
    $card = $ogDeck[$cardkey];
    $round["player"]["cards"][] = $card;
    $round["player"]["cardValues"] = $card["value"];
    if ($card["value"] == 11) {
        $round["player"]["aces"]++;
    }

    # dealer flop card
    $cardkey = array_pop($deckKeys);
    $card = $ogDeck[$cardkey];

    $round["dealer"]["cards"][] = $card;
    $round["dealer"]["cardValues"] = $card["value"];
    if ($card["value"] == 11) {
        $round["dealer"]["aces"]++;
    }

    # ai flop card
    if ($setup["multiPlayer"]) {
        $cardkey = array_pop($deckKeys);
        $card = $ogDeck[$cardkey];

        $round["ai"]["cards"][] = $card;
        $round["ai"]["cardValues"] = $card["value"];
        if ($card["value"] == 11) {
            $round["ai"]["aces"]++;
        }
    }
}

# ------------- PLAYER GAME PLAY ------------

# player hits

if (!$newDeal && $page =="play" && $round["hitstand"] == "hit") {
    # new player card
    $cardkey = array_pop($deckKeys);
    $card = $ogDeck[$cardkey];
    $round["player"]["cards"][] = $card;
    if ($card["value"] == 11) {
        $round["player"]["aces"]++;
    }
    $score = $round["player"]["cardValues"] + $card["value"];

    # edge case : player over 21 but has aces
    if ($score > 21 && $round["player"]["aces"] > 0) {
        $score -= 10;
        $round["player"]["aces"] --;
    }

    # end round automatically: blackjack or bust!
    if ($score == 21) {
        $round["player"]["winlose"] = "Win";
        $stats["blackjacks"] ++;
        $endRound = true;
    }
    if ($score > 21) {
        $round["player"]["winlose"] = "Bust";
        $endRound = true;
    }

    # update the player"s round details
    $round["player"]["cardValues"] = $score;
    $round["hitstand"] = null;
}

# player stand
if (!$newDeal && $page =="play" && $round["hitstand"] == "stand") {
    $endRound = true;
}


# ------------- AI GAME PLAY ------------

# end of player round - AI automated game play if ption is selected
if ($endRound && $setup["multiPlayer"]) {
    # default toggle player must hit (boolean default)
    $isHit = 1;

    # player continues to receive additional cards until a winner is decided or player stands
    while ($isHit < 8) {
        # local variable score (for readability only)
        $dealerValue = $round["dealer"]["cardValues"];

        # local variable of card"s point value (for readability)
        $score = $round["ai"]["cardValues"];

        # edge case : dealer over 21 but has aces
        if ($score > 21 && $round["ai"]["aces"] > 0) {
            # Ace is now worth 1 point
            $score -= 10;
            $round["ai"]["aces"] --;
            $round["ai"]["cardValues"] -= 10;
        }

        # stand or hit decision (simple logic)
        if ($score >= 15) {
            $isHit += 10;
        }

        if ($isHit < 8) {
            $cardkey = array_pop($deckKeys);
            $card = $ogDeck[$cardkey];
            $round["ai"]["cards"][] = $card;
            $round["ai"]["cardValues"] = $round["ai"]["cardValues"] + $card["value"];
            if ($card["value"] == 11) {
                $round["ai"]["aces"]++;
            }
        }

        $isHit ++;
    }
}



# ------------- DEALER GAME PLAY ------------

# end of round - Dealer must play
if ($endRound) {
    # dealer hits default is true
    $isHit = 1;

    # Dealer"s turn action - use rule max 7 cards or end if dealer stands
    while ($isHit < 8) {
        # local variable of card"s point value (for readability)
        $score = $round["dealer"]["cardValues"];

        # edge case : dealer over 21 but has aces
        if ($score > 21 && $round["dealer"]["aces"] > 0) {
            # Ace is now worth 1 point
            $score -= 10;
            $round["dealer"]["aces"] --;
            $round["dealer"]["cardValues"] -= 10;
        }

        # basic stand decision (stand if dealer has 17+ points including bust)
        if ($score >= $round["dealer"]["standValue"]) {
            $isHit += 10;
        }

        # dealer must hit
        if ($isHit < 8) {
            $cardkey = array_pop($deckKeys);
            $card = $ogDeck[$cardkey];
            $round["dealer"]["cards"][] = $card;
            $round["dealer"]["cardValues"] = $round["dealer"]["cardValues"] + $card["value"];
            if ($card["value"] == 11) {
                $round["dealer"]["aces"]++;
            }
        }

        $isHit ++;
    }
    $endRound = true;
}


# ------------- DETERMINE WINNERS OF THE ROUND ------------

# Find if player wins (if not already blackjack or bust)
if ($endRound && strlen($round["player"]["winlose"]) < 1) {
    $dealer = $round["dealer"]["cardValues"];
    $player = $round["player"]["cardValues"];
    $round["player"]["winlose"] = winRound($dealer, $player);
}

# Find if ai wins vs dealer
if ($endRound) {
    $dealer = $round["dealer"]["cardValues"];
    $player = $round["ai"]["cardValues"];
    $round["ai"]["winlose"] = winRound($dealer, $player);
    if ($round["ai"]["cardValues"] > 21) {
        $round["ai"]["winlose"] = "Bust";
    }
}


# ------------- END OF ROUND ------------

# end of round - Close the round and pay the winner
if ($endRound) {
    $stats["rounds"] ++;
    if ($round["player"]["winlose"] == "Tie") {
        $setup["cash"] += $round["player"]["wager"];
        $stats["ties"] ++;
    } elseif ($round["player"]["winlose"] == "Win") {
        $setup["cash"] += 2 * $round["player"]["wager"];
        $stats["wins"] ++;
    } else {
        $stats["loses"] ++;
    }
    $round["winner"] = true;
}


# ------------- UPDATE SESSIONS ------------
$_SESSION["setup"] = $setup;
$_SESSION["page"] = $page;
$_SESSION["round"] = $round;
$_SESSION["deckKeys"] = $deckKeys;
$_SESSION["stats"] = $stats;


# ------------- DISPAY VIEWS ------------
# index-view will include secondary views depending on the page to display
require "index-view.php";