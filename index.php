<?php
require_once('./config.php');

# retrieve the request's body and parse it as JSON and load the event
$input = @file_get_contents("php://input");
$logger->info('Incoming Stripe event.', json_decode($input, TRUE));
$event = json_decode($input);

# only interested in charge.succeeded calls
if ($event->type != 'charge.succeeded') {
    exit;
}

$charge = $event->data->object;

# form request
$fbExpenseRequest = new FbExpenseRequest($charge, $fb);
$request = array( 'expense' => $fbExpenseRequest->request() );
$logger->debug('New FreshBooks Expense request', $request);

# make api call
$fb->setMethod('expense.create');
$fb->post($request);
$fb->request();

# handle the response
if($fb->success()) {
    $logger->info('FreshBooks expense was created', $fb->getResponse());
    http_response_code(200);
} else {
    $logger->error('FreshBooks expense was not created.', $fb->getResponse());
    http_response_code(422);
}


