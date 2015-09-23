<?php
require_once('vendor/autoload.php');

// Dotenv
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();


// Logger
$logger = new Katzgrau\KLogger\Logger(__DIR__.'/log');


// Stripe
$stripe = array(
    "secret_key"      => $_ENV['STRIPE_SECRET_KEY'],
    "publishable_key" => $_ENV['STRIPE_SECRET_KEY']
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);