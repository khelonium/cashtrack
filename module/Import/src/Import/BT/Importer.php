<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */



namespace Import\BT;

use Finance\Transaction\Repository as TransactionService;

class Importer
{
    private $parser;

    /**
     * @var Finance\Transaction\Service|null
     */
    private $service   = null;



    /**
     * @param TransactionService $transactionService
     * @param $merchants used to identify transactions FIXME - merchants not nice here
     */
    public function __construct(TransactionService $transactionService , Parser $parser )
    {
        $this->service = $transactionService;
        $this->parser  = $parser;
    }

    /**
     * This map is hardcoded now but will be refactored to use labels
     * @var array
     */
    private $map = array(
            'RO81BTRL06601201514881XX' => '52',
            'RO76BTRL06701202T18825XX' => '47'
    );

    public function import($file)
    {

        $transactions = $this->parser->parse($file);

        $parts   =  explode ("-",$file);
        $parts   =  explode ("/", $parts[0]);
        $account =  array_pop($parts);

        foreach ($transactions as $transaction) {
            $transaction->fromAccount = $this->map[$account];
            $this->service->add($this->mapToEntity($transaction));

        }
    }

    /**
     *
     */
    private function mapToEntity(\Import\BT\Transaction $transaction)
    {
        $entity = new \Finance\Transaction\Transaction();

        $entity->amount = $transaction->getAmount();

        $entity->reference   = $transaction->transactionRef;
        $entity->to_account  = $transaction->toAccount;
        $entity->date        = $transaction->transactionDate;
        $entity->description = $transaction->description;
        $entity->from_account = $transaction->fromAccount;

        return $entity;
    }
}
