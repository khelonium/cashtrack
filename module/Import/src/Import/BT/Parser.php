<?php
/**
* @author Cosmin Dordea
*/

namespace Import\BT;


class Parser
{

    /**
    * @var Matcher
    */
    private $matcher = null;

    /**
    * Result Object to use
    */
    public function __construct(Matcher $matcher = null)
    {
        $this->matcher = $matcher;
    }

    /**
     * @param $file
     * @throws \Exception
     * @return array
     */
    public function parse($file)
    {
        if (!is_file($file)) {
            throw new \Exception("File $file not found");
        }

        $fh      = fopen($file, "r");
        $result  = array();

        while ($line = fgetcsv($fh)) {
            $transaction =  new Transaction($line);
            if ($transaction->isValid()) {
                $result[] = $transaction;
            }
        }

        array_walk($result, $this->getMatchStrategy());

        return $result;
    }

    /**
     * @return callable
     */
    private function getMatchStrategy()
    {
        $matcher = $this->matcher;

        return function ($transaction) use ($matcher) {
            if ($matcher) {
                $matcher->match($transaction);
            }
        };
    }



}
