<?php

namespace App\Controllers;

class HelperController extends Controller
{
    # convert the player's card keys to hmtl format (e.g. key 0 back to 2♦)
    public static function getCardDisplay($cardString, $ogDeck)
    {
        $cardKeys = explode(",", $cardString);
        foreach ($cardKeys as $key) {
            $returnData[] = $ogDeck[$key];
        }

        return $returnData;
    }

    # sum the player's cards
    public static function getCardsValue($cardString, $ogDeck)
    {
        $cardsValue = 0;
        $aces = 0;
        $cardKeys = explode(",", $cardString);
        foreach ($cardKeys as $key) {
            $cardsValue += $ogDeck[$key]['value'];
            $aces = ($ogDeck[$key]['value'] > 10) ? ($aces += 1) : $aces;
        }

        # adjust for aces (Brad's formula)- need to repeat in case of 3+ aces
        if ($cardsValue > 21 && $aces > 0) {
            $cardsValue -= $aces * 10;
            if ($cardsValue <= 11) {
                $cardsValue -= 10;
            }
            if ($cardsValue <= 11) {
                $cardsValue -= 10;
            }
        }

        return $cardsValue;
    }

    # deck of cards in order (key 0 = 2♦ ... key 51 = A♠)
    public static function createDeck()
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
}