<?php

//https://www.w3schools.com/php/php_oop_constructor.asp

class Person
{
    # Properties
    public $firstName;
    public $lastName;
    public $age;

    public function __construct(string $firstName, string $lastName, int $age)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
    }

    public function getFullName()
    {
        return $this->firstName . " " . $this->lastName;
    }

    public function getClassification()
    {
        if ($this->age >= 18) {
            return "adult";
        }
        return "minor";
    }
}