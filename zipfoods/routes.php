<?php

# Define the routes of your application

return [
    # Ex: The path `/` will trigger the `index` method within the `AppController`
    '/' => ['AppController', 'index'],
    '/about' => ['AppController', 'about'],
    '/contact' => ['AppController', 'contact'],
    '/products' => ['ProductsController', 'index'],
    '/product' => ['ProductsController', 'show'],
    '/products/save-review' => ['ProductsController', 'saveReview'],
    '/practice' => ['AppController', 'practice'],
    '/products/new' => ['ProductsController', 'newProduct'],
    '/products/save-product' => ['ProductsController', 'SaveProduct'],
];