<?php
/**
* @author Cosmin Dordea
*/
namespace Import\BT;


class Matcher
{


    private $merchants = array();

    private $atm     = null;

    private $unknown = null;

    /**
     * @param array $merchants
     */
    public function __construct(array $merchants)
    {
        $this->merchants = $merchants;

        foreach ($this->merchants as $merchant) {
            if ($merchant->identifier == 'atm') {
                $this->atm = $merchant;
            }

            if ($merchant->identifier == 'unknown') {
                $this->unknown = $merchant;
            }
        }
    }

    public function match(Transaction $transaction)
    {


        foreach ($this->merchants as $merchant) {
            if (strpos($transaction->description, $merchant->identifier) !== false) {
                $transaction->toAccount = $merchant->accountId;
            }
        }

        if (null == $transaction->toAccount && $transaction->isExpense()) {
            $transaction->toAccount = $this->unknown->accountId;
        }


    }

}

