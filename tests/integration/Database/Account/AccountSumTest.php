<?php
namespace Database\Account;

use Database\Transaction\Repository;
use Finance\Account\Account;
use Refactoring\Time\Interval\ThisYear;

class AccountSumTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AccountSum
     */
    private $accountSum;

    /**
    * @before
    */
    public function itCanConstruct()
    {
        $account = new Account();
        $account->id = 4;
        $account->name = 'mancare';
        $this->accountSum = new AccountSum();
        $this->accountSum->setAccount($account);

        $this->accountSum->setDbAdapter(\TestBootstrap::get('Zend\Db\Adapter\Adapter'));

        foreach ($this->getRepository()->all() as $transaction) {
            $this->getRepository()->delete($transaction);
        }

    }

    /**
     * @test
     */
    public function withNoTransactionsThereWillBeNoTotals()
    {
        $this->assertEquals(0, count($this->accountSum->totalFor(new ThisYear())));
    }

    /**
     * @test
     */
    public function withOneTransactionTheTotalWillBeTheSame()
    {
        $this->getRepository()->create(
            [
                'toAccount' =>4,
                'fromAccount' =>1,
                'amount' =>10,
                'transaction_date' => (new \DateTime())->format('Y-m-d')
            ]
        );

        $summaries = $this->accountSum->totalFor(new ThisYear());

        $this->assertEquals(10, $summaries->first()->amount);
    }

    /**
     * @test
     */
    public function alternateQueryMethodWorks()
    {
        $this->getRepository()->create(
            [
                'toAccount' =>5,
                'fromAccount' =>1,
                'amount' =>10,
                'transaction_date' => (new \DateTime())->format('Y-m-d')
            ]
        );

        $account = new Account();
        $account->id = 5;
        $account->name = 'stuff';
        $summaries = $this->accountSum->forAccount($account)->totalFor(new ThisYear());

        $this->assertEquals(10, $summaries->first()->amount);
    }

    /**
     * @test
     */
    public function itGroupsSeveralTransactions()
    {

        for ($i = 0; $i < 10; $i++) {
            $this->getRepository()->create(
                [
                    'toAccount' =>4,
                    'fromAccount' =>1,
                    'amount' =>10,
                    'transaction_date' => (new \DateTime())->format('Y-m-d')
                ]
            );

            $summaries = $this->accountSum->totalFor(new ThisYear());
            $this->assertEquals(($i+1) * 10, $summaries->first()->amount);


        }

    }


    /**
     * @test
     */
    public function itGroupsByMonth()
    {
        $first = new \DateTime();
        $second = (new \DateTime())->sub(new \DateInterval('P31D'));



        $this->getRepository()->create(
            [
                'toAccount' =>4,
                'fromAccount' =>1,
                'amount' =>10,
                'transaction_date' => $first->format('Y-m-d')
            ]
        );

        $this->getRepository()->create(
            [
                'toAccount' =>4,
                'fromAccount' =>1,
                'amount' =>10,
                'transaction_date' => $second->format('Y-m-d'),

            ]
        );


        $totals = $this->accountSum->totalFor(new ThisYear());

        $this->assertEquals(2, count($totals));

        $this->assertEquals($second->format('Y-m-01'), $totals->first()->month);

    }

    /**
     * @return Repository
     */
    private function getRepository()
    {
        return \TestBootstrap::get('\Database\Transaction\Repository');
    }
}
