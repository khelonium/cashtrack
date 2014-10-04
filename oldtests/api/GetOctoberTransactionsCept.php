<?php
$I = new ApiGuy($scenario);
$I->wantTo('GET transactions for October');
$I->sendGET('transaction?month=2013-10-01');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('"id":"387","amount":"1453","fromAccount":"54","toAccount":"48","description":"387-description","date":"2013-10-10","reference":"');;
