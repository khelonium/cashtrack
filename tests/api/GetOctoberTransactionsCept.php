<?php
$I = new ApiGuy($scenario);
$I->wantTo('GET transactions for October');
$I->sendGET('transaction?month=2013-10-01');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('Blender altex');
