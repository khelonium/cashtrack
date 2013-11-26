<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/24/13
 * Time: 9:44 AM
 * To change this template use File | Settings | File Templates.
 */

namespace FinanceTest\Balance;


use Finance\Balance\Balance;

class BalanceTest extends \PHPUnit_Framework_TestCase
{

    public function testStateZero()
    {
        $balance = new Balance(array());

        $this->assertEquals(0, $balance->getBalance());
        $this->assertEquals(0, $balance->getCredit());
        $this->assertEquals(0, $balance->getDebit());
    }
}
