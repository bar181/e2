<?php

function vowelCount($word)
{
    $results = 0;
    $vowels = ['a','e','i','o','u'];

    // https://www.w3schools.com/php/func_string_strtoupper.asp#:~:text=The%20strtoupper()%20function%20converts,converts%20a%20string%20to%20lowercase
    $word = strtolower($word);

    foreach ($vowels as $vowel) {
        // https://www.php.net/manual/en/function.substr-count.php
        $results += substr_count($word, $vowel);
    }

    return $results;
}


# ---------- Tests ----------
$example1 = vowelCount('apple'); # Should yield 2
$example2 = vowelCount('lynx'); # Should yield 0
$example3 = vowelCount('hi'); # Should yield 1
$example4 = vowelCount('mississippi'); # Should yield 4
$example5 = vowelCount('APPLE 3.1415'); # Should yield 2


echo "<br>Week 7 Q2 vowelCount Results<br> ";
echo "<br> example1 yields 2: " . $example1;
echo "<br> example2 yields 0: " . $example2;
echo "<br> example3 yields 1: " . $example3;
echo "<br> example4 yields 4: " . $example4;
echo "<br> example5 yields 2: " . $example5;