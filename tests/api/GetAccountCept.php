<?php
$I = new ApiGuy($scenario);
$I->wantTo('GET account information');
$I->sendGET('account',array('id'=>1));
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('"name":"mancare"');

