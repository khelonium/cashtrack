<?php
/**
* @author Cosmin Dordea
*/
namespace Import\BT;

class Transaction
{


    public $transactionDate = null;
    public $description     = null;
    public $transactionRef  = null;
    public $debit           = null;
    public $credit          = null;
    public $sold            = null;

    private $valid          = true;
    private $errorMessage   = null;

    public $toAccount       = null;
    public $fromAccount     = null;



   /**
        array (
          0 => 'Data tranzactie',
          1 => 'Data valuta:',
          2 => 'Descriere:',
          3 => 'Referinta tranzactiei',
          4 => 'Debit',
          5 => 'Credit',
          6 => 'Sold contabil',

    */
    public function __construct(array $data)
    {

        if ( count($data) < 7) {
            $this->valid        = false;
            $this->errorMessage = "Invalid number of fields";
            return;
        }

        $this->transactionDate = $data[0];
        $this->description    = $data[2];
        $this->transactionRef = $data[3];
        $this->debit          = $this->toFloat($data[4]);
        $this->credit         = $data[5];
        $this->sold           = $data[6];

    }

    public function getAmount()
    {
        if ($this->isIncome()) {
            return $this->toFloat($this->credit);
        }

        return -1 * $this->toFloat($this->debit);

    }

    public function getDescription()
    {
        return $this->description;
    }

    private function toFloat($value)
    {
        return (float)str_replace(',','',$value);
    }

    public function isValid()
    {
        return $this->valid;
    }

    public function getErrorMessage()
    {
        if ($this->isValid()) {
            throw new \Exception("You should not call this message if object is valid");
        }

        return $this->errorMessage;
    }

    public function isIncome()
    {
        return $this->credit > 0;
    }


    public function isExpense()
    {

        return $this->debit < 0;
    }



    public function __toString()
    {
        $representation = $this->description."\t";

        if ($this->isExpense()) {
            $representation .= "expense\t";
            $representation  .= $this->debit;

        } elseif ($this->isIncome()) {
            $representation .= "income\t";
            $representation  .= $this->credit;
        }

        return $representation;
    }






}
