<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Balance;



use Database\AccountValue\AccountValueFactory;
use Finance\AccountValue\AccountValueFactoryAwareTrait;
use Refactoring\Interval\SpecificMonth;
use Zend\Stdlib\ArrayObject;

class OpenBalance extends AbstractBalance
{

    /**
     * @var \Refactoring\Interval\SpecificMonth
     */
    private $interval        = null;

    /**
     * @var \Database\AccountValue\AccountValueFactory
     */
    private $accountFactory = null;

    public function __construct(SpecificMonth $month, $accountProvider)
    {
        $this->interval        = $month;
        $this->accountFactory = $accountProvider;
    }

    /**
     * @return SpecificMonth
     */
    public function getMonth()
    {
        return $this->interval;
    }

    /**
     * @var null
     */
    private $list = null;

    /**
     * @return array|null
     */
    public function accounts()
    {
        if ($this->list === null ) {
            $this->list = $this->accountFactory->forAllAccounts($this->interval);
        }

        return $this->list;
    }



}