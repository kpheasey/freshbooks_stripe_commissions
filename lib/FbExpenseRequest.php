<?php

class FbExpenseRequest {

    private $charge;
    private $fb;

    function __construct($charge, $fb) {
        $this->charge = $charge;
        $this->fb = $fb;
    }

    function request() {
        $request = array(
            'staff_id' => 1,
            'category_id' => $this->categoryId(),
            'amount' => $this->amount(),
            'vendor' => 'Stripe',
            'date' => $this->date(),
            'notes' => $this->notes()
        );

        return $request;
    }

    function notes() {
        $notes = $this->charge->description;
        $notes = chop($notes, ' - ');
        return $notes;
    }

    function date() {
        $date = date('c', $this->charge->created);
        return $date;
    }

    function categoryId() {
        $fbCategory = FbCategory::findByName($this->fb, 'Commissions');
        $categoryId = $fbCategory->getId();
        return $categoryId;
    }

    function amount() {
        $balanceTransaction = Stripe\BalanceTransaction::retrieve($this->charge->balance_transaction);
        $amount = $balanceTransaction->fee / 100;
        return $amount;
    }

}