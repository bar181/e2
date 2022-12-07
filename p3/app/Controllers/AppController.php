<?php

namespace App\Controllers;

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

        $rounds = $this->app->db()->all('rounds');

        return $this->app->view('history', [
            'rounds' => $rounds
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
        $player = $this->app->db()->findById('players', $player_id);

        # check for end of game (cash nil or double starting cash)
        if ($player['cash'] < 10 || $player['cash'] > ($player['startcash'] * 2)) {
            $this->app->redirect('/gameover');
        }

        return $this->app->view('wager', [
            'player' => $player
        ]);
    }

     public function play()
     {
         # show game play - player selects hit or stand
         # validate - prevent direct URL access
         # process: get cards to display (new deal or existing cards)
         # params: cards, score

         dump($this->app);

         # get round_id (param)
         if ($this->app->old('player_id')) {
             $round_id = $this->app->old('round_id');
             $player_id = $this->app->old('player_id');
         } else {
             # redirect - direct URL entered in browser
             $this->app->redirect('/');
         }

         # get current hand data
         $handsData = $this->app->db()->findByColumn('hands', 'round_id', '=', $round_id)[0];
         if (is_null($handsData['pcards'])) {
             $dealCards = self::startingHands();
         }

         dump('handsData', $handsData);
     }

     public function startingHands()
     {
         dump('startingHands');
         return "TODO";
     }
}