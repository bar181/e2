<?php

namespace App\Controllers;

use App\Controllers\HelperController;

class AppController extends Controller
{
    /**
     * Controller for direct URL access points
     */

    public function index()
    {
        # start of game process
        # allow user to set name, cash, and multiplayer

        # CONST for single player game (i.e. no registration process)
        $player_id = 1;
        $playerData = $this->app->db()->findById('players', $player_id);

        return $this->app->view('index', [
            'player' => $playerData
        ]);
    }

    public function history()
    {
        # show summary stats and history of all rounds

        $player_id = 1;

        $player = $this->app->db()->findById('players', $player_id);
        $rounds = $this->app->db()->all('rounds');
        $statsSummary = self::statsSummary();

        return $this->app->view('history', [
            'rounds' => $rounds,
            'player' => $player,
            'statsSummary' => $statsSummary,
        ]);
    }
    public function winner()
    {
        # show winner page if player doubles money

        $player_id = 1;

        $playerData = $this->app->db()->findById('players', $player_id);
        if ($playerData['cash'] > ($playerData['startcash'] * 2)) {
            return $this->app->view('winner');
        }

        $this->app->redirect('/');
        return;
    }

    public function round()
    {
        # show details for a specific round

        $id = $this->app->param('id');
        $round = $this->app->db()->findById('rounds', $id);

        # add result for display purposes (prevent front end code)
        # preference for nested ternary
        $round['result'] =  $round['win'] ? 'Win' : ($round['tie'] ? 'Tie' : ($round['loss'] ? 'Loss' : 'Loss: Did not finish round '));

        return $this->app->view('round', [
            'round' => $round
        ]);
    }

    public function wager()
    {
        # show wager page
        $player_id = 1;
        $playerData = $this->app->db()->findById('players', $player_id);

        # check for end of game (cash nil or double starting cash)
        if ($playerData['cash'] < 10 || $playerData['cash'] > ($playerData['startcash'] * 2)) {
            $this->app->redirect('/');
            return;
        }

        return $this->app->view('wager', [
            'player' => $playerData
        ]);
    }

     public function play()
     {
         # show game play - player selects hit or stand
         # validate - redirect to wager if player's process is set to "done"
         # process: get cards to display (new deal or existing cards)

         $player_id = 1;
         $playerData = $this->app->db()->findById('players', $player_id);
         $round_id = $playerData['round_id'];
         $play_process = $playerData['play'];

         # new hand process
         if ($play_process == "new") {
             # update database with new deck of cards and assign hold/flop cards
             self::startNewHand($round_id, $playerData);
             $playerData['play'] = "play";
             self::updateTableById($playerData, 'players');
         }

         # original deck: show (HTML cards display), value (card points), style (black or red)
         $ogDeck = HelperController::createDeck();
         $handsData = $this->app->db()->findByColumn('hands', 'round_id', '=', $round_id)[0];

         # display values for HMTL
         $round["player"]["cards"] = HelperController::getCardDisplay($handsData['pcards'], $ogDeck);
         $round["player"]["score"] = $handsData['pscore'];
         $round["dealer"]["cards"] = HelperController::getCardDisplay($handsData['dcards'], $ogDeck);
         $round["dealer"]["score"] = $handsData['dscore'];

         if ($playerData['multiplayer'] > 0) {
             $round["ai"]["cards"] = HelperController::getCardDisplay($handsData['aicards'], $ogDeck);
             $round["ai"]["score"] = $handsData['aiscore'];
         }

         # add current results to round
         $round['player']['result'] = $handsData['presult'];
         $round['dealer']['result'] = $handsData['dresult'];
         $round['ai']['result'] = $handsData['airesult'];

         $statsSummary = self::statsSummary();

         return $this->app->view('play', [
            'player' => $playerData,
            'round' => $round,
            'statsSummary' => $statsSummary,
        ]);
     }

     public function statsSummary()
     {
         # get player totals: win, tie, loss, games to display
         $allRounds = $this->app->db()->all('rounds');
         $statsSummary['win'] = 0;
         $statsSummary['tie']= 0;
         $statsSummary['loss'] = 0;
         $statsSummary['games'] = sizeof($allRounds);

         foreach ($allRounds as $row) {
             $statsSummary['win'] += $row['win'];
             $statsSummary['tie'] += $row['tie'];
             $statsSummary['loss'] += $row['loss'];
         }

         return $statsSummary;
     }


    public function startNewHand($round_id, $playerData)
    {
        # creates the initial cards to start the game
        # deals cards to all players
        # updates results in hands table

        # get existing hands data based on the round
        $handsData = $this->app->db()->findByColumn('hands', 'round_id', '=', $round_id)[0];

        # shuffle a new deck of cards
        $ogDeck = HelperController::createDeck();
        $deckKeys = array_keys($ogDeck);
        shuffle($deckKeys);

        # player hold card
        $cardkey = array_pop($deckKeys);
        $handsData['pcards'] = $cardkey;
        $handsData['pscore'] = $ogDeck[$cardkey]["value"];

        # dealer hold card
        $cardkey = array_pop($deckKeys);
        $handsData['dcards'] = $cardkey;
        $handsData['dscore'] = $ogDeck[$cardkey]["value"];

        # ai hold card
        $handsData['aicards'] = "";
        $handsData['aiscore'] = 0;
        if ($playerData['multiplayer'] > 0) {
            $cardkey = array_pop($deckKeys);
            $handsData['aicards'] = $cardkey;
            $handsData['aiscore'] = $ogDeck[$cardkey]["value"];
        }

        # player flop card
        $cardkey = array_pop($deckKeys);
        $handsData['pcards'] .= "," . $cardkey;
        $handsData['pscore'] = HelperController::getCardsValue($handsData['pcards'], $ogDeck);

        # update database with new hand data
        $handsData['deckkeys'] = implode(",", $deckKeys);
        self::updateTableById($handsData, 'hands');
    }

    public function updateTableById($updateDataArray, $tableName)
    {
        # updates the table $tableName for row $updateDataArray['id']
        # updates all fields in $updateDataArray
        # uses framework's run method

        $setQuery = "";
        foreach (array_keys($updateDataArray) as $key) {
            if ($key != "id") {
                $setQuery .=  $key . " = :" . $key . ", ";
            }
        }
        $setQuery = rtrim($setQuery, ", ");

        # update timestamp if key exists
        if (array_key_exists("timestamp", $updateDataArray)) {
            $updateDataArray['timestamp'] = date("Y-m-d H:i:s");
        }

        $sql = "UPDATE " . $tableName . "
                SET " . $setQuery . "
                WHERE id = :id ";

        # use framework PDO format to update all fields
        $this->app->db()->run($sql, $updateDataArray);
        return;
    }
}