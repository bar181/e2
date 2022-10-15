<?php 
require 'Catalog.php';
echo "<br> classes";
$catalog = new Catalog('products.json');

echo "<br><br>";
var_dump($catalog->names); # Array of product data

echo "<br>EXAMPLE<br>";
echo "<br><br>";
var_dump($catalog->getAll()); # Array of product data

echo "<br><br>";
var_dump($catalog->products); # Array of product data

echo "<br><br>";
var_dump(count($catalog->searchByName('cheerios'))); # 1

echo "<br><br>";
var_dump($catalog->getById(5)); # Details for Honey Nut Cheerios

echo "<br><br>";
var_dump($catalog->getById(99)); # NULL