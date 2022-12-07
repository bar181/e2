<?php

namespace App\Commands;

use Faker\Factory;

class AppCommand extends Command
{
    public function fresh()
    {
        $this->migrate();
        $this->seed();
    }

    public function migrate()
    {
        $this->app->db()->createTable('players', [
            'name' => 'varchar(255)',
            'startcash' => 'int',
            'cash' => 'int',
            'multiplayer' => 'tinyint(1)',
            'ailevel' => 'int',
            'timestamp' => 'timestamp'
        ]);


        $this->app->db()->createTable('rounds', [
            'player_id' => 'int',   // foreign key player
            'win' => 'tinyint(1)',
            'tie' => 'tinyint(1)',
            'loss' => 'tinyint(1)',
            'wager' => 'int',
            'timestamp' => 'timestamp'
        ]);


        $this->app->db()->createTable('hands', [
            'player_id' => 'int',   // foreign key player
            'round_id' => 'int',    // foreign key rounds
            'pcards' => 'varchar(255)',
            'dcards' => 'varchar(255)',
            'aicards' => 'varchar(255)',
            'pscore' => 'int',
            'dscore' => 'int',
            'aiscore' => 'int',
            'presult' => 'varchar(4)',
            'dresult' => 'varchar(4)',
            'airesult' => 'varchar(4)',
            'timestamp' => 'timestamp'
        ]);

        dump("migrate done");
    }

    public function seed()
    {
        # defaults
        $faker = Factory::create();

        $winCards = implode(",", [13, 10]);
        $tieCards = implode(",", [8, 9]);
        $lossCards = implode(",", [2, 5, 9, 10]);
        $winScore = 20;
        $tieScore = 17;
        $lossScore = 23;
        $player_id = 1;

        # add player data
        $playerData = [
            'name' => 'Jack Black',
            'startcash' => 100,
            'cash' => 100,
            'multiplayer' => 0,
            'ailevel' => 0,
            'timestamp' => $faker->dateTimeBetween('-10 days', '-10 days')->format('Y-m-d H:i:s'),

        ];
        $this->app->db()->insert('players', $playerData);

        # simulate 10 rounds played by player 1
        for ($i = 0; $i < 10; $i++) {
            $playerResult = rand(0, 2);
            $wager = rand(0, 2) * 10 + 10;
            $win = 0;
            $tie = 0;
            $loss = 0;
            $presult = "Tie";
            $dresult = "Tie";
            // faker example source course video: https://www.youtube.com/embed/b4b7RF9A3HU?rel=0&showinfo=0
            $timestamp = $faker->dateTimeBetween('-' . (10 - $i) . ' days', '-' . (10 - $i) . ' days')->format('Y-m-d H:i:s');

            if ($playerResult == 0) {
                # player win
                $win = 1;
                $pscore = $winScore;
                $dscore = $lossScore ;
                $pcards = $winCards;
                $dcards = $lossCards;
                $presult = "Win";
                $dresult = "Loss";
            } elseif ($playerResult == 1) {
                # tie
                $tie = 1;
                $pscore = $tieScore;
                $dscore = $tieScore ;
                $pcards = $tieCards;
                $dcards = $tieCards;
            } else {
                # player loss
                $loss = 1;
                $pscore = $lossScore;
                $dscore = $winScore ;
                $pcards = $lossCards;
                $dcards = $winCards;
                $presult = "Loss";
                $dresult = "Win";
            }

            # add simulation to rounds
            $roundData = [
                'player_id' => $player_id,   // foreign key player
                'win' => $win,
                'tie' => $tie,
                'loss' => $loss,
                'wager' => $wager,
                'timestamp' => $timestamp,
            ];

            $this->app->db()->insert('rounds', $roundData);

            # add simulation to hands
            $handData = [
                'player_id' => $player_id,  // foreign key player
                'round_id' => ($i + 1),     // foreign key rounds - starts at 1
                'pcards' => $pcards,
                'dcards' => $dcards,
                'pscore' => $pscore,
                'dscore' => $dscore,
                'presult' => $presult,
                'dresult' => $dresult,
                'timestamp' => $timestamp,
            ];

            $this->app->db()->insert('hands', $handData);
        }

        dump("seed done");
        dump("select * from players;");
        dump("select * from rounds;");
        dump("select * from hands;");
    }
}