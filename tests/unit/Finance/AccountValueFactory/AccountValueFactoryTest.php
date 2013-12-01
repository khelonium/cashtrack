<?php
namespace FinanceTest\AccountValueFactory;
use Codeception\Util\Stub;
use Finance\AccountValue\AccountValue;
use Finance\AccountValue\AccountValueFactory;
use Refactoring\Interval\CurrentWeek;
use Refactoring\Interval\SpecificMonth;
use Zend\Db\Adapter\Adapter;

class AccountValueFactoryTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * NOT really unit test here
     */
    public function testFactoryMethod()
    {
        $factory = $this->getFromFactory();
        $this->assertTrue($factory instanceof AccountValueFactory);
    }

    public function testConstructWithNoFactory()
    {
        try {
            $factory = new AccountValueFactory();
        } catch (\Exception $e) {
            $this->fail('No exception expected at construct');
        }


        try {
            $factory->get(1, new CurrentWeek() );
        } catch (\RuntimeException $e) {
            $this->assertEquals("Zend Db Adapter not set", $e->getMessage());
        }
    }



    public function testConstructWithNoFactoryAndAdapterSet()
    {
        try {
            $factory = new AccountValueFactory();

            $factory->setDbAdapter($this->getAdapter());
        } catch (\Exception $e) {

            $this->fail('No exception expected at construct '.$e->getMessage());
        }


        try {
            $factory->get(1, new CurrentWeek() );
        } catch (\RuntimeException $e) {
            $this->assertEquals("Account Factory not configured", $e->getMessage());
        }
    }


    public function testAccountValueFromDatabaseForAccount1AndMonthOctober()
    {
        $expected_credit = '1893.00';
        $factory = $this->getFromFactory();

        $interval = new SpecificMonth(new \DateTime('2013-10-01'));
        $accountValue = $factory->get(1,$interval);

        $this->assertTrue($accountValue instanceof AccountValue);
        $this->assertEquals($accountValue->getCredit(), $expected_credit);

    }


    /**
     * fixme refactor using stubs
     * @return Adapter
     */
    private function getAdapter()
    {

        return $this->getFromService('\Zend\Db\Adapter\Adapter');
    }

    /**
     * fixme refactor using stubs
     * @return AccountValueFactory
     */
    private function getFromFactory()
    {

        return $this->getFromService('\Finance\AccountValue\AccountValueFactory');
    }


    private function getFromService($key)
    {
        return \ApplicationTest\Bootstrap::getServiceManager()->get($key);

    }
}