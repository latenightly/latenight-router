<?php

require_once 'Router.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$app->get('/', function () use ($app) {
    return $app->render('home', ['title' => 'Home']);
});

$app->get('/hello/:name', function (string $name) use ($app) {
    return $app->render('hello', ['name' => $name]);
});

$app->post('/submit', function () {
    // Your code to handle form submissions or other actions
    return "Form submitted";
});

// Run the router
echo $app->run();
