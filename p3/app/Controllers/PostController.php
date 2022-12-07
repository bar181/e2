<?php

namespace App\Controllers;

class PostController extends Controller
{
    public function post_play()
    {
        $player_id = $this->app->input('player_id');

        $this->app->redirect('/play', ['player_id' => $player_id]);
        // dump('rounds', $this->app->db()->all('players'), $this->app->db()->all('rounds'), $this->app->db()->all('hands'));
    }

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

        # update players table: adjust cash
        $sql = "UPDATE players 
                SET 
                    cash = :cash,
                    timestamp = :timestamp
                WHERE id = :player_id ";

        $playersData = [
            'cash' => $player['cash'] - $wager,
            'timestamp' => date("Y-m-d H:i:s"),
            'player_id' => $player_id
        ];
        $this->app->db()->run($sql, $playersData);

        # all good - open play
        $this->app->redirect('/play', ['player_id' => $player_id, 'round_id' => $round_id]);
    }
}