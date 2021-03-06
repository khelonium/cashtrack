<?php
namespace Overflow;

use Finance\Account\Account;
use Finance\Account\AccountSum;
use Finance\Cashflow\AccountTotal;
use Library\Collection;
use Refactoring\Time\Interval;
use Watch\AccountOverflow;

class AccountOverflowTest extends \PHPUnit_Framework_TestCase
{

    /**
    * @test
    */
    public function noOverflowUnlessConfigured()
    {
        $overflow = new AccountOverflow(new AccountSumStub());

        $this->assertFalse($overflow->account($this->getAccount())->isAbove(100));
    }


    /**
     * @test
     */
    public function weDontOverflowIfTotalIsLessThanConfigured()
    {
        $overflow = new AccountOverflow(new AccountSumStub());
        $this->assertFalse($overflow->account($this->getAccount())->isAbove(100));
    }

    /**
     * @test
     */
    public function weUseAnAccountTotalToGetData()
    {

        $spy      = new AccountSumSpy();
        $overflow = new AccountOverflow($spy);

        $this->assertFalse($overflow->account($this->getAccount())->isAbove(100));

        $this->assertTrue($spy->wasCalled);
        $this->assertTrue($spy->forAccountCalled);
    }


    /**
     * @test
     */
    public function ifWeHaveASumBellowConfigurationWeDoNotOverflow()
    {
        $summer = new AccountSumStub();

        $monthTotal         = new AccountTotal();
        $monthTotal->amount = 50;
        $monthTotal->accountId  = 4;



        $collection = new Collection([$monthTotal]);
        $summer->willReturn = $collection;

        $overflow = new AccountOverflow($summer);

        $this->assertFalse($overflow->account($this->getAccount())->isAbove(100));


    }


    /**
     * @test
     */
    public function weOverflowIfSumIsMoreThanConfigured()
    {
        $summer = new AccountSumStub();

        $monthTotal         = new AccountTotal();
        $monthTotal->amount = 101;
        $monthTotal->accountId  = 4;



        $collection = new Collection([$monthTotal]);
        $summer->willReturn = $collection;

        $overflow = new AccountOverflow($summer);

        $this->assertTrue($overflow->account($this->getAccount())->isAbove(100));

    }

    /**
     * @test
     */
    public function weKnowWhenWeAlmostOverflow()
    {
        $summer = new AccountSumStub();

        $monthTotal         = new AccountTotal();
        $monthTotal->amount = 76;
        $monthTotal->accountId  = 4;


        $collection = new Collection([$monthTotal]);
        $summer->willReturn = $collection;

        $overflow = new AccountOverflow($summer);

        $this->assertTrue($overflow->account($this->getAccount())->isAlmostAbove(100));

    }

    /**
     * @test
     */
    public function weCanUseADifferentTimeInterval()
    {

        $overflow = new AccountOverflow(new StrategySpy(), new Interval\ThisYear());

        $overflow->account($this->getAccount())->isAbove(100);

    }

    /**
     * @return Account
     */
    protected function getAccount()
    {
        $account       = new Account();
        $account->id   = 4;
        $account->name = 'mancare';

        return $account;
    }
}

class AccountSumSpy implements AccountSum
{
    public $wasCalled = false;
    public $forAccountCalled = false;

    /**
     * @param Interval $interval
     * @return Collection
     */
    public function totalFor(Interval $interval)
    {
        $this->wasCalled = true;
        return new Collection([]);
    }

    public function setAccount(Account $account = null)
    {
        // TODO: Implement setAccount() method.
    }

    public function forAccount(Account $account)
    {
        $this->forAccountCalled = true;
        return $this;
    }
}

class StrategySpy extends AccountSumSpy
{
    public function totalFor(Interval $interval)
    {
        if (!$interval instanceof Interval\ThisYear) {
            throw new \Exception("Unexpected interval");
        }
        return new Collection([]);
    }




}

class AccountSumStub implements \Finance\Account\AccountSum
{

    public $willReturn = null;

    public function __construct()
    {
        $this->willReturn = new Collection([]);
    }


    /**
     * @param Interval $interval
     * @return Collection
     */
    public function totalFor(Interval $interval)
    {
        return $this->willReturn;
    }

    public function setAccount(Account $account = null)
    {
        // TODO: Implement setAccount() method.
    }

    public function forAccount(Account $account)
    {
        return $this;
    }

}