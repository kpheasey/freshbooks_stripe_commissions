<?php
require_once('./vendor/autoload.php');

// Dotenv
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();


// Logger
$logger = new Katzgrau\KLogger\Logger(__DIR__.'/log');


// Stripe
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);