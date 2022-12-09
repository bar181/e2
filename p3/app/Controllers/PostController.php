<?php

namespace App\Controllers;

use App\Controllers\HelperController;

class PostController extends Controller
{
    # This class includes the form post pages for
    # setup: updates the player's cash, name and multiple options
    # wager: initializes a new round
    # play: game play based on if a user hits or stands

    public function post_setup()
    {
        # user enters their name, cash and multiplayer select
        # validate inputs
        # update players table
        # send player to wager page

        $this->app->validate([
            'name' => 'required|minLength:2',
            'cash' => 'required|numeric|min:10|max:1000',
        ]);

        # current user details
        $player_id = 1;

        # update players table with inputs
        $sql = "UPDATE players 
                SET 
                    name = :name, 
                    cash = :cash,
                    startcash = :startcash,
                    ailevel = :ailevel,
                    multiPlayer = :multiPlayer,
                    timestamp = :timestamp
                WHERE id = :player_id ";

        $data = [
            'name'=> $this->app->input('name'),
            'cash' => $this->app->input('cash'),
            'startcash' => $this->app->input('cash'),
            'ailevel' => rand(1, 3),
            'multiPlayer' => ($this->app->input('multiPlayer')) ? 1 : 0,
            'timestamp' => date("Y-m-d H:i:s"),
            'player_id' => $player_id,
        ];

        $this->app->db()->run($sql, $data);

        # all good send player to wager page
        $this->app->redirect('/wager');
    }


    public function post_wager()
    {
        # user enters their wager amount (10 or 50)
        # validate inputs
        # update database
        #   players: reduce cash
        #   rounds: initialize
        #   hands: initialize
        # send user to play page

        $player_id = 1;

        # validation error returns redirect
        # validate player_id to prevent JS manipulation
        $this->app->validate([
            'wager' => 'required',
            'player_id' => 'required|numeric',
        ]);

        # required variables
        $wager = $this->app->input('wager');
        $player = $this->app->db()->findById('players', $player_id);

        # ensure wager is more than player cash
        # form handles this but need to prevent JS form manipulation
        if ($player['cash'] < $wager) {
            $this->app->redirect('/gameover');
        }

        # insert rounds table: wager, nil for win/tie/loss
        $roundsData = [
            'player_id' => $player_id,
            'wager' => $wager,
            'win' => 0,
            'tie' => 0,
            'loss' => 0,
            'timestamp' => date("Y-m-d H:i:s"),
        ];
        $round_id = $this->app->db()->insert('rounds', $roundsData);

        # insert hands table: set default
        $handsData = [
            'player_id' => $player_id,
            'round_id' => $round_id,
            'timestamp' => date("Y-m-d H:i:s"),
        ];
        $this->app->db()->insert('hands', $handsData);

        # update players table: adjust cash and round level
        $sql = "UPDATE players 
                SET 
                    cash = :cash,
                    round_id = :round_id,
                    play = :play,
                    timestamp = :timestamp
                WHERE id = :player_id ";

        $playersData = [
            'cash' => $player['cash'] - $wager,
            'round_id' => $round_id,
            'play' => "new",
            'timestamp' => date("Y-m-d H:i:s"),
            'player_id' => $player_id
        ];
        $this->app->db()->run($sql, $playersData);

        # all good - open play
        $this->app->redirect('/play', ['player_id' => $player_id]);
    }

    public function post_play()
    {
        $player_id = 1;
        $endRound = true;

        # get database data (player, hand)
        $playerData = $this->app->db()->findById('players', $player_id);
        $round_id = $playerData['round_id'];
        $handsData = $this->app->db()->findByColumn('hands', 'round_id', '=', $round_id)[0];

        # player hit or stand
        $hitstand = $this->app->input('hitstand');

        # unaltered deck of cards
        $ogDeck = HelperController::createDeck();

        # current deck of cards - array format
        $deckKeys = explode(",", $handsData['deckkeys']);

        # player hits
        if ($hitstand === "hit") {
            $endRound = false;

            # player adds card
            $cardkey = array_pop($deckKeys);
            $handsData['pcards'] .= "," . $cardkey;
            $handsData['pscore'] = HelperController::getCardsValue($handsData['pcards'], $ogDeck);

            # end game if blackjack or bust
            if ($handsData['pscore'] >= 21) {
                $handsData['presult'] = ($handsData['pscore'] > 21) ? "Bust" : "Win";
                $endRound = true;
            }
        }

        # check if round is over
        if ($endRound) {
            # end of round action - ai and dealer play
            # update database tables
            self::endOfRoundProcess($playerData, $handsData, $deckKeys);
        } else {
            # player continues to play
            # save update to hands table and return to play URL
            $handsData['deckkeys'] = implode(",", $deckKeys);
            self::updateTableById($handsData, 'hands');
        }

        $this->app->redirect('/play');
    }


    public function getResults($score, $dealerScore)
    {
        # returns - win, los or tie vs dealer's score
        if ($dealerScore > 21 || $score > $dealerScore) {
            return "Win";
        }
        if ($score == $dealerScore) {
            return "Tie";
        }
        return "Loss";
    }


    public function endOfRoundProcess($playerData, $handsData, $deckKeys)
    {
        $ogDeck = HelperController::createDeck();
        $round_id = $playerData['round_id'];

        # do work for multiplayer option
        if ($playerData['multiplayer'] > 0) {
            # default settings for ai
            $isHit = 1;
            $ailevel = $playerData['ailevel'];
            $dealerCard = $handsData['dscore'];


            # ai game play (hit/stand decision based on ai agression level)
            while ($isHit < 8) {
                $handsData['aiscore'] = HelperController::getCardsValue($handsData['aicards'], $ogDeck);

                # stand or hit decision (based on ai level)
                if ($handsData['aiscore'] >= 12 && $ailevel < 2) {
                    $isHit += 10;
                }
                if ($handsData['aiscore'] >= 15 && $ailevel == 2) {
                    $isHit += 10;
                }
                if ($handsData['aiscore'] >= 17 && $ailevel > 2) {
                    $isHit += 10;
                }

                if ($isHit < 8) {
                    $cardkey = array_pop($deckKeys);
                    $handsData['aicards'] .= "," . $cardkey;
                }
                $isHit ++;
            }

            # update ai results if blackjack or bust
            $handsData['aiscore'] = HelperController::getCardsValue($handsData['aicards'], $ogDeck);
            if ($handsData['aiscore'] >= 21) {
                $handsData['airesult'] = ($handsData['aiscore'] > 21) ? "Bust" : "Win";
            }
        }

        # dealer play
        $isHit = 1;

        while ($isHit < 8) {
            $handsData['dscore'] = HelperController::getCardsValue($handsData['dcards'], $ogDeck);

            # stand or hit decision (dealer must stand on 17 - hard coded constant)
            if ($handsData['dscore'] >= 17) {
                $isHit += 10;
            }

            if ($isHit < 8) {
                $cardkey = array_pop($deckKeys);
                $handsData['dcards'] .= "," . $cardkey;
            }
            $isHit ++;
        }

        # update dealer results
        $handsData['dscore'] = HelperController::getCardsValue($handsData['dcards'], $ogDeck);
        $handsData['dresult'] = ($handsData['dscore'] > 21) ? "Bust" : "Play";

        # update player and ai results
        if (is_null($handsData['presult'])) {
            $handsData['presult'] = self::getResults($handsData['pscore'], $handsData['dscore']);
        }

        if ($playerData['multiplayer'] > 0 && is_null($handsData['airesult'])) {
            $handsData['airesult'] = self::getResults($handsData['aiscore'], $handsData['dscore']);
        }


        # update the hands table
        $handsData['deckkeys'] = implode(",", $deckKeys);
        self::updateTableById($handsData, 'hands');

        # update the rounds table: win, loss or tie
        # update the player's cash value
        $roundsData = $this->app->db()->findById('rounds', $round_id);
        if ($handsData['presult'] == "Win") {
            $roundsData['win'] = 1;
            $playerData['cash'] += $roundsData['wager'] * 2;
        } elseif ($handsData['presult'] == "Tie") {
            $roundsData['tie'] = 1;
            $playerData['cash'] += $roundsData['wager'] ;
        } else {
            $roundsData['loss'] = 1;
        }
        self::updateTableById($roundsData, 'rounds');

        # update the player's cash value and process to done
        $playerData['play'] = "done";
        self::updateTableById($playerData, 'players');
    }


   public function updateTableById($updateDataArray, $tableName)
   {
       # updates the table $tableName for row $updateDataArray['id']
       # updates all fields in $updateDataArray

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