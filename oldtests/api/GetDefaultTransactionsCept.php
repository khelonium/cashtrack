<?php
$I = new ApiGuy($scenario);
$I->wantTo('GET transactions');
$I->sendGET('transaction');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('[]');
