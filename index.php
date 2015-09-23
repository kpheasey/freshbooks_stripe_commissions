<?php
require_once('./config.php');

// Retrieve the request's body and parse it as JSON and load the event
$input = @file_get_contents("php://input");
$logger->info('Incoming Stripe event.', json_decode($input, TRUE));

$event = json_decode($input);
$event = $event->data->object;

$fbCategory = FbCategory::findByName($fb, 'Commissions');

$request = array( 'expense' =>
    array(
        'staff_id' => 1,
        'category_id' => $fbCategory->getId(),
        'amount' => ($event->amount / 100),
        'vendor' => 'Stripe',
        'date' => date('c', $event->created),
        'notes' => $event->description
    )
);
$logger->debug('New FreshBooks Expense request', $request);

$fb->setMethod('expense.create');
$fb->post($request);
$fb->request();

if($fb->success()) {
    $logger->info('FreshBooks expense was created', $fb->getResponse());
    http_response_code(200);
} else {
    $logger->error('FreshBooks expense was not created.', $fb->getResponse());
    http_response_code(422);
}


