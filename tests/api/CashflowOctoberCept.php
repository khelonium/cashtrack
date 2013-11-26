<?php
$I = new ApiGuy($scenario);
$I->wantTo('GET Cashflow list');
$I->sendGET('cashflow',array('month' => '2013-10-01'));
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('{"name":"alocatie","accountId":"53","amount":"3700.00","type":"income","month":"2013-10-01"}');
$I->seeResponseContains('{"name":"yopeso","accountId":"54","amount":"14100.00","type":"income","month":"2013-10-01"}');
