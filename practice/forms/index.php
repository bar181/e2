<?php 

session_start();

if(!is_null($_SESSION['results'])) {
    $results = $_SESSION['results'];
    $haveAnswer = $results['haveAnswer'];
    $correct = $results['correct'];
    $_SESSION['results'] = null;
}


$words = [ 
    'evidence' => 'A discovery that helps solve a crime',
    'ponder' => 'To think carefully about something', 
    'locate' => 'Discover the exact place or position of something or someone',
    'abridge' => 'to shorten by leaving out some parts', 
    'regulate' => 'to make rules or laws that control something', 
    'modest' => 'not overly proud or confident', 
    'impromptu' => 'not prepared ahead of time', 
     'stint' => 'a period of time spent at a particular activity', 
    ]; 

    // https://www.php.net/manual/en/function.shuffle.php
    $keys = array_keys($words);
    shuffle($keys);
    $realWord = $keys[0];
    $phrase = $words[$realWord];

    //https://www.php.net/manual/en/function.str-shuffle.php
    $scrambleWord = str_shuffle($realWord);

    $_SESSION['clue'] = [
        'realWord' => $realWord,
        'phrase' => $phrase,
        'scrambleWord' => $scrambleWord,
    ];

    // echo "<br>";
    // var_dump($_SESSION);
    // echo "<br>";


require 'index-view.php';