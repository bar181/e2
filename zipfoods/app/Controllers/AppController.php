<?php

namespace App\Controllers;

class AppController extends Controller
{
    /**
     * This method is triggered by the route "/"
     */
    public function index()
    {
        return $this->app->view('index');
    }


    public function about()
    {
        return $this->app->view('about');
    }


    public function contact()
    {
        $email = "zipfoods@brad.ross.me";

        return $this->app->view('contact', [
            'email' => $email]);

        // test Q 3
        // return $this->app->view('x/y/z');
    }


    public function practice()
    {
        # Set up all the variables we need to make a connection
        $host = $this->app->env('DB_HOST');
        $database = $this->app->env('DB_NAME');
        $username = $this->app->env('DB_USERNAME');
        $password = $this->app->env('DB_PASSWORD');

        # DSN (Data Source Name) string
        # contains the information required to connect to the database
        $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";

        # Driver-specific connection options
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            # Create a PDO instance representing a connection to a database
            $pdo = new \PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        $sql = "SELECT * FROM products";

        # Execute the statement, getting the result set as a PDOStatement object
        # https://www.php.net/manual/en/pdo.query.php
        $statement = $pdo->query($sql);

        # https://www.php.net/manual/en/pdostatement.fetchall.php
        dump($statement->fetchAll());


        $sql = "INSERT INTO products (name, sku, description, price, available, weight, perishable) 
        VALUES (
            'Driscoll’s Strawberries', 
            'driscolls-strawberries',
            'Driscoll’s Strawberries are consistently the best, sweetest, juiciest strawberries available. This size is the best selling, as it is both convenient for completing a cherished family recipes and for preparing a quick snack straight from the fridge.',
            4.99, 
            0, 
            1,
            1)";

        // $pdo->query($sql);


        $sqlTemplate = "INSERT INTO products (name, sku, description, price, available, weight, perishable) 
        VALUES (:name, :sku, :description, :price, :available, :weight, :perishable)";

        $values = [
            'name' => 'Driscoll’s Strawberries',
            'sku' => 'driscolls-strawberries',
            'description' => 'Driscoll’s Strawberries are consistently the best, sweetest, juiciest strawberries available. This size is the best selling, as it is both convenient for completing a cherished family recipes and for preparing a quick snack straight from the fridge.',
            'sku' => 'driscolls-strawberries',
            'price' => 4.99,
            'available' => 0,
            'weight' => 1,
            'perishable' => 1,
        ];

        // $statement = $pdo->prepare($sqlTemplate);
        // $statement->execute($values);


        // Q2
        // CREATE TABLE `reviews` (
        //     `id` INT NOT NULL AUTO_INCREMENT ,
        //     `name` VARCHAR(255) NOT NULL ,
        //     `sku` VARCHAR(255) NOT NULL ,
        //     `review` TEXT NOT NULL ,
        //     PRIMARY KEY (`id`)
        //     );

        //     DESCRIBE reviews;
    }
}