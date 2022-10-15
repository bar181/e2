<?php

class Catalog
{
    # Properties
    public $products = [];
    public $names; // GOOD
    // public $name = 'Jane'; // GOOD
    // public $names = ['Jane', 'Avi', 'Jamal', 'Natalia'];  // GOOD
    // public names = file_get_contents('products.json'); // BAD
    
    
    # Methods
    public function __construct(string $jsonSource)
    {
        # Load the JSON string of data
        $json = file_get_contents($jsonSource);
        $this->names = file_get_contents('products.json');  //return string
        // $this->names = json_decode($names, true); // assoc array

        # Convert the JSON string into an array
        $this->products = json_decode($json, true);
    }

    public function getById(int $id)
    {
        return $this->products[$id] ?? null;
    }

    public function getAll()
    {
        return $this->products;
    }

    public function searchByName(string $term)
    {
        $results = [];
        foreach ($this->products as $product) {
            if (strstr(strtolower($product['name']), strtolower($term))) {
                $results[] = $product;
            }
        }

        return $results;
    }
}