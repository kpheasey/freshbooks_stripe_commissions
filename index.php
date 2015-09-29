<?php
require_once('./config.php');

// Retrieve the request's body and parse it as JSON and load the event
$input = @file_get_contents("php://input");
$logger->info('Incoming Stripe event.', json_decode($input, TRUE));

$event = json_decode($input);

if ($event->type != 'charge.succeeded') {
    exit;
}

$charge = $event->data->object;

# amount
$balanceTransaction = Stripe\BalanceTransaction::retrieve($charge->balance_transaction);
$fee = $balanceTransaction->fee / 100;

# category_id
$fbCategory = FbCategory::findByName($fb, 'Commissions');
$categoryId = $fbCategory->getId();

# date
$date = date('c', $charge->created);

# notes
$notes = $charge->description;
$notes = chop($notes, ' - ');

# form request
$request = array( 'expense' =>
    array(
        'staff_id' => 1,
        'category_id' => $categoryId,
        'amount' => $fee,
        'vendor' => 'Stripe',
        'date' => $date,
        'notes' => $notes
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


