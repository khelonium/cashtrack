<?php

$I = new ApiGuy($scenario);
$I->wantTo("see June");
$I->sendGET('balance/2013-06-01');
$I->seeResponseContains('"debit":143707');
$I->seeResponseContains('"credit":4647');
