<?php

$I = new ApiGuy($scenario);

$I->wantTo('close June');
$I->sendPOST('balance', array('month' => '2013-06-01'));
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

$I->seeInDatabase(
    'transaction',
    array('from_account' => 55, 'to_account' => 47, 'amount' => 139060,'transaction_date' =>'2013-07-01')
);

$I->seeResponseContains('"debit":143707');
$I->seeResponseContains('"credit":4647');
