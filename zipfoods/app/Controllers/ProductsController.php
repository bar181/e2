<?php

namespace App\Controllers;

use App\Products;

class ProductsController extends Controller
{
    private $productsObj;

    # Create a construct method to set up a productsObj property that can be used across different methods
    public function __construct($app)
    {
        parent::__construct($app);
        $this->productsObj = new Products($this->app->path('database/products.json'));
    }


    public function index()
    {
        $products = $this->productsObj->getAll();

        return $this->app->view('products/index', [
            'products' => $products
        ]);
    }


    public function show()
    {
        // dump($_GET);
        $sku = $this->app->param('sku');
        if (is_null($sku)) {
            $this->app->redirect('/products');
        }

        $product = $this->productsObj ->getBySku($sku);

        $reviewSaved = $this->app->old('reviewSaved');

        if (is_null($product)) {
            $missingProduct = trim(str_replace("-", " ", $sku));
            return $this->app->view('products/missing', ['product' => $missingProduct ]);
        }

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

        # https://phpdelusions.net/pdo_examples/select
        $stmt = $pdo->prepare("SELECT * FROM reviews WHERE sku=?");
        $stmt->execute([$sku]);
        $reviews = $stmt->fetchAll();

        // dump($reviews);

        return $this->app->view('products/show', [
            'product' => $product,
            'reviewSaved' => $reviewSaved,
            'reviews' => $reviews,
        ]);
    }

    public function saveReview()
    {
        $this->app->validate([
            'sku' => 'required',
            'name' => 'required',
            'review' => 'required|minLength:20'
        ]);

        # validation error returns redirect

        $sku = $this->app->input('sku');
        $name = $this->app->input('name');
        $review = $this->app->input('review');

        # Q2 Set up PDO

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

        # Post review
        $sqlTemplate = "INSERT INTO reviews (sku, name, review) 
        VALUES (:sku, :name, :review)";

        $values = [
            'sku' => $sku ,
            'name' => $name,
            'review' => $review,
        ];

        $statement = $pdo->prepare($sqlTemplate);
        $statement->execute($values);

        $this->app->redirect('/product/?sku=' . $sku, ['reviewSaved' => true]);
    }
}