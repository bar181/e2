<?php

# Define the routes of your application

return [

    '/' => ['AppController', 'index'],
    '/post_setup' => ['PostController', 'post_setup'],
    '/wager' => ['AppController', 'wager'],
    '/post_wager' => ['PostController', 'post_wager'],
    '/play' => ['AppController', 'play'],
    '/post_play' => ['PostController', 'post_play'],
    '/history' => ['AppController', 'history'],
    '/round' => ['AppController', 'round'],

];