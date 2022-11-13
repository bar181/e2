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
}