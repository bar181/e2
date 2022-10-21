<?php

require 'Person.php';
echo "<br> Week 7 Q4 Person Class<br>";

$person = new Person('John', 'Harvard', 45);


echo "<br>";
echo $person->firstName; # Should print "John"

echo "<br>";
echo $person->getFullName(); # Should print "John Harvard"

echo "<br>";
echo $person->getClassification(); # Should print "adult"

// var_dump($person); // shows type is object (q7)

// $example = (5 > 10) ? 9 : 10;

// echo "<br>";
// echo $example;  // 10 (question 11)