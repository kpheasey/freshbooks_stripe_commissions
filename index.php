<?php
require_once('./config.php');

// Retrieve the request's body and parse it as JSON
$input = @file_get_contents("php://input");
$event_json = json_decode($input);

print_r($input);
//$logger->debug('Incoming Stripe event.', $event_json);

http_response_code(200); // PHP 5.4 or greater
