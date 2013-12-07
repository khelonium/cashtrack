<?php

$I = new ApiGuy($scenario);

$I->wantTo('close June');
$I->sendPOST('balance', array('month' => '2013-06-01'));
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

$I->seeResponseContains('"debit":143707');
$I->seeResponseContains('"credit":4647');
