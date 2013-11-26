<?php
$I = new ApiGuy($scenario);
$I->wantTo('GET Cashflow list');
$I->sendGET('cashflow');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('[]');
