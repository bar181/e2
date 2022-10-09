<?php

session_start();

$answer = $_POST['answer'];
$haveAnswer = true;
$correct = false;


if($answer == '') {
    $haveAnswer = false;
} 
if ($answer == $_SESSION['clue']['realWord']){
    $correct = true;
}

$_SESSION['results'] = [
    'haveAnswer' => $haveAnswer,
    'correct' => $correct,
];

// echo "<br>PROCESS <br><br>";
// var_dump($_SESSION);
// echo "<br>";

header('Location: index.php');