<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/16/15
 * Time: 9:26 PM
 */

namespace integration\Database\Account;


use Database\Account\AccountSum;
use Database\Transaction\Repository;
use Finance\Account\Account;
use Refactoring\Time\Interval\ThisYear;

class BalanceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var AccountSum
     */
    private $balance;

    /**
    * @before
    */
    public function itCanConstruct()
    {
        $account = new Account();
        $account->id = 4;
        $account->name = 'mancare';
        $this->balance = new AccountSum($account);

        $this->balance->setDbAdapter(\TestBootstrap::get('Zend\Db\Adapter\Adapter'));
        foreach ($this->getRepository()->all() as $transaction) {
            $this->getRepository()->delete($transaction);
        }

    }

    /**
     * @test
     */
    public function withNoTransactionsThereWillBeNoTotals()
    {
        $this->assertEquals(0, count($this->balance->totalFor(new ThisYear())));
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

        $summaries = $this->balance->totalFor(new ThisYear());

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

            $summaries = $this->balance->totalFor(new ThisYear());
            $this->assertEquals(($i+1) * 10, $summaries->first()->amount);


        }

    }


    /**
     * @test
     */
    public function itGroupsByMonth()
    {
        $first = new \DateTime();
        $second = (new \DateTime())->sub(new \DateInterval('P1M'));

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


        $totals = $this->balance->totalFor(new ThisYear());

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
