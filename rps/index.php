<?php

require __DIR__.'/vendor/autoload.php';

use RPS\Game;
use App\Debug;

use App\MyGame;

# TESTING FOR SESSIONS
// echo "<br>Testing MyGame <br>";
// $game = new MyGame();
// Debug::dump($game->play('heads'));

// echo "<br>Testing Game Sessions <br>";
// $game2 = new Game(true, 10);
// $game2->play('rock');
// $game2->play('rock');
// $game2->play('rock');
// $game2->play('rock');
// $game2->play('rock');
// $game2->play('rock');
// $game2->play('rock');
// $game2a = $game2->getResults();
// Debug::dump($game2a);


// echo "<br>CLEAR<br>";
// $game2->clearResults();
// $game2a = $game2->getResults();
// Debug::dump($game2a);

// echo "<br>REDO <br>";
// $game2->play('rock');
// $game2a = $game2->getResults();
// Debug::dump($game2a);


$moves = ['rock', 'paper', 'scissors'];
$wins = 0;
$ties = 0;
$loses = 0;
// $totalRounds = 0;


for ($i = 0; $i < 10; $i++) {
    $game = new Game();
    shuffle($moves);
    $result = $game->play($moves[0]);
    $wins = ($result["outcome"] == "won") ? $wins + 1 : $wins;
    $ties = ($result["outcome"] == "tie") ? $ties + 1 : $ties;
    $loses = ($result["outcome"] == "lost") ? $loses + 1 : $loses;

    $results[] = $result;
    // $totalRounds ++;
}
$totalRounds = $i;

require "index-view.php";


# See full array of activity
// Debug::dump($results);