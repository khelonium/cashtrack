<?php
$I = new ApiGuy($scenario);
$I->wantTo('want to get balance for September 2013');
$I->sendGET('balance',array('month' => '2013-09-01'));
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('"debit":"102978.00"');

