<?php
require_once('./vendor/autoload.php');

// Lib
foreach (glob("./lib/*.php") as $filename)
{
    require_once($filename);
}


// Dotenv
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();


// Logger
$logger = new Katzgrau\KLogger\Logger(__DIR__.'/log');


// FreshBooks
$fb = new Freshbooks\FreshBooksApi($_ENV['FRESHBOOKS_DOMAIN'], $_ENV['FRESHBOOKS_TOKEN']);


// Stripe
ini_set("allow_url_fopen", true);
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);