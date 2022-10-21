<?php


session_start();

# user pressed quit - clear all sessions and refresh page
if (isset($_GET['quit'])) {
    $_SESSION['setup'] = null;
    $_SESSION['page'] = null;
    $_SESSION['round'] = null;
    $_SESSION['deckKeys'] = null;
}

# set up page - clear old sessions and get set up variables
if (isset($_POST['setup'])) {
    # reset all sessions
    $_SESSION= null;

    $multiPlayer = (isset($_POST['multiPlayer'])) ? true : false;

    $_SESSION['setup'] = [
           'playerName' => $_POST['playerName'],
           'cash' => intval($_POST['cash']),
           'wager' => 0,
           'multiPlayer' => $multiPlayer,
           'round' => 1,
       ];

    $_SESSION['page'] = 'newRound';
}


# newRound page - show round details, user picks wager for next round
if (isset($_POST['newRound'])) {
    # reset all sessions
    $wager = ($_POST['wager'] == 'w50') ? 50 : 10;

    $_SESSION['setup']['wager'] = $wager;

    $_SESSION['page'] = 'play';
}

# play page - use
if (isset($_POST['play'])) {
    # reset all sessions

    $_SESSION['page'] = 'play';
}



echo "<br>_GET: ";
var_dump($_GET);
echo "<br>_POST: <br>";
var_dump($_POST);
echo "<br>_SESSION: <br>";
var_dump($_SESSION);




header('Location: index.php');