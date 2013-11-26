<?php
$I = new ApiGuy($scenario);
$I->wantTo('GET Cashflow list');
$I->sendGET('cashflow',array('month' => '2013-10-01'));
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('{"name":"1-account","accountId":"1","amount":"1893.00","type":"expense","month":"2013-10-01"}');
