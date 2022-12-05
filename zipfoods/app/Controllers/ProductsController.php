<?php

namespace App\Controllers;

use App\Products;

class ProductsController extends Controller
{
    public function index()
    {
        // $products = $this->productsObj->getAll();
        $products = $this->app->db()->all('products');

        return $this->app->view('products/index', [
            'products' => $products
        ]);
    }

    public function show()
    {
        $sku = $this->app->param('sku');
        if (is_null($sku)) {
            $this->app->redirect('/products');
        }

        $productQuery = $this->app->db()->findByColumn('products', 'sku', '=', $sku);
        $reviewSaved = $this->app->old('reviewSaved');

        // I added the missing product sku in the return (not in class notes)
        if (empty($productQuery)) {
            $missingProduct = trim(str_replace("-", " ", $sku));
            return $this->app->view('products/missing', ['product' => $missingProduct ]);
        } else {
            $product = $productQuery[0];
        }
        $productId = intVal($product['id']);
        $reviews = $this->app->db()->findByColumn('reviews', 'product_id', '=', $productId);

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
            'product_id' => 'required',
            'name' => 'required',
            'review' => 'required|minLength:20'
        ]);

        # validation error returns redirect

        $sku = $this->app->input('sku');
        $product_id = $this->app->input('product_id');
        $name = $this->app->input('name');
        $review = $this->app->input('review');

        $this->app->db()->insert('reviews', [
            'product_id' => $product_id ,
            'name' => $name,
            'review' => $review,
        ]);

        $this->app->redirect('/product/?sku=' . $sku, ['reviewSaved' => true]);
    }


    public function newProduct()
    {
        // return $this->app->view('products/newproduct');

        $productSaved = $this->app->old('productSaved');
        $sku = $this->app->old('sku');

        return $this->app->view('products/newproduct', [
            'productSaved' => $productSaved,
            'sku' => $sku,
        ]);
    }


    public function SaveProduct()
    {
        $this->app->validate([
            'name' => 'required',
            'sku' => 'required|alphaNumericDash',
            'description' => 'required',
            'price' => 'required|numeric',
            'available' => 'required|digit',
            'weight' => 'required|numeric',
            'perishable' => 'required|digit|max:1',
        ]);
        # validation error returns redirect

        # my long way do not need to save all the inputs, can use inputAll()

        // $name = $this->app->input('name');
        // $sku = $this->app->input('sku');
        // $description = $this->app->input('description');
        // $price = $this->app->input('price');
        // $available = $this->app->input('available');
        // $weight = $this->app->input('weight');
        // $perishable = $this->app->input('perishable');


        // $this->app->db()->insert('products', [
        //     'name' => $name ,
        //     'sku' => $sku ,
        //     'description' => $description ,
        //     'price' => $price ,
        //     'available' => $available ,
        //     'weight' => $weight ,
        //     'perishable' => $perishable ,
        // ]);

        # alt way if do not have all the fields
        // $newProducts = [
        //     'name' => $this->app->input('name'),
        //     'sku' => $this->app->input('sku'),
        //     ....
        // ]
        // $this->app->db()->insert('products', $newProducts);


        $this->app->db()->insert('products', $this->app->inputAll());


        $this->app->redirect('/products/new', [
            'productSaved' => true,
            'sku' => $this->app->input('sku'),

        ]);
    }
}