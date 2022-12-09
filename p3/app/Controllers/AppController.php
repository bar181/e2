<?php

namespace App\Controllers;

use App\Controllers\HelperController;

class AppController extends Controller
{
    /**
     * This method is triggered by the route "/"
     */
    public function index()
    {
        # start of game process
        # allow user to set name, cash, and multiplayer
        # params: player (from players table)

        # CONST for single player game (i.e. no registration process)
        $player_id = 1;

        $player = $this->app->db()->findById('players', $player_id);
        // dump("index", $player);

        return $this->app->view('index', [
            'player' => $player
        ]);
    }

    public function history()
    {
        # show history of all rounds

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

    public function round()
    {
        # show details for a specific round

        $id = $this->app->param('id');
        $round = $this->app->db()->findById('rounds', $id);

        # add result for display purposes (prevent front end code)
        # preference for nest ternary
        $round['result'] =  $round['win'] ? 'Win' : ($round['tie'] ? 'Tie' : ($round['loss'] ? 'Loss' : 'Loss: Did not finish round '));

        return $this->app->view('round', [
            'round' => $round
        ]);
    }

    public function wager()
    {
        # show wager page or end of game
        # params: player (from players table)

        $player_id = 1;
        $playerData = $this->app->db()->findById('players', $player_id);

        # check for end of game (cash nil or double starting cash)
        if ($playerData['cash'] < 10 || $playerData['cash'] > ($playerData['startcash'] * 2)) {
            $this->app->redirect('/gameover');
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
         # params: cards, score

         $player_id = 1;
         $playerData = $this->app->db()->findById('players', $player_id);
         $round_id = $playerData['round_id'];
         $play_process = $playerData['play'];

         # new hand process
         if ($play_process == "new") {
             # update database with new deck of cards and assign hold/flop cards
             self::startNewHand($round_id, $playerData);
         }

         # origial deck: show (HTML display), value (card points), style (black or red)
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
        # call function to update hands database

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
        self::postNewHand($handsData, $deckKeys);
    }

    public function updatePlayerPlay($player_id, $playValue)
    {
        # updates the players database table for play
        $sql = "UPDATE players 
                SET 
                    play = :play,
                    timestamp = :timestamp
                WHERE id = :player_id ";

        $updateData = [
            'play' => $playValue,
            'timestamp' => date("Y-m-d H:i:s"),
            'player_id' => $player_id
        ];
        $this->app->db()->run($sql, $updateData);
    }

     public function postNewHand($handsData, $deckKeys)
     {
         # posts new hands data to database
         # updates the player's process

         self::updatePlayerPlay($handsData['player_id'], 'Play');


         $sql = "UPDATE hands 
                SET 
                    pcards = :pcards,
                    dcards = :dcards,
                    aicards = :aicards,
                    pscore = :pscore,
                    dscore = :dscore,
                    aiscore = :aiscore,
                    deckkeys = :deckkeys,
                    timestamp = :timestamp
                WHERE id = :hand_id ";

         $updateData = [
             'pcards' => $handsData['pcards'],
             'dcards' => $handsData['dcards'],
             'aicards' => $handsData['aicards'],
             'pscore' => $handsData['pscore'],
             'dscore' => $handsData['dscore'],
             'aiscore' => $handsData['aiscore'],
             'deckkeys' => implode(",", $deckKeys),
             'timestamp' => date("Y-m-d H:i:s"),
             'hand_id' => $handsData['id']
         ];
         $this->app->db()->run($sql, $updateData);

         return;
     }
}