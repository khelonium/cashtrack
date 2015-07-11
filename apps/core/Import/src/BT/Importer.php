<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */



namespace Import\BT;

use Database\Transaction\Repository as TransactionRepository;

class Importer
{
    private $parser;

    /**
     * @var TransactionRepository
     */
    private $repo   = null;



    /**
     * @param TransactionRepository $repo
     */
    public function __construct(TransactionRepository $repo, Parser $parser )
    {
        $this->repo = $repo;
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

        $account = $this->getAccount($file);

        $out = [];

        foreach ($transactions as $transaction) {
            $transaction->fromAccount = $this->map[$account];
            $entity = $this->mapToEntity($transaction);
            $out[] = $entity;
            $this->repo->add($entity);

        }

        return $out;
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

    /**
     * @param $file
     * @return mixed
     */
    protected function getAccount($file)
    {
        $parts = explode("-", $file);
        $parts = explode("/", $parts[0]);
        $account = array_pop($parts);
        return $account;
    }
}
