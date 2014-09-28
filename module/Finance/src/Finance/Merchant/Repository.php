<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */




namespace Finance\Merchant;



use Refactoring\Repository\AbstractRepository;

class Repository extends AbstractRepository
{

    public function addFromName($identifier, $name = null)
    {

        if (null == $name) {
            $name = $identifier;
        }
        if (!is_string($name) || !is_string($identifier)) {
            throw new \InvalidArgumentException(" Provided argument is not string");
        }

        return $this->add(new Merchant(array('name' => $name)));
    }


    /**
     * @param string $identifier
     * @return array|\Entity|null
     */
    public function find($identifier)
    {
        $result = $this->gateway()->select(array('identifier' => $identifier));

        if (count($result) == 0 ) {
            return null;
        }

        return $result->current();
    }


    /**
     * @var \Zend\Db\TableGateway\TableGateway
     */
    private $gateway = null;

    /**
     * @return \Zend\Db\TableGateway\TableGateway
     */
    protected function gateway()
    {
        if (null == $this->gateway) {
            $this->gateway = $this->getServiceManager()->get('\Finance\Dao\MerchantGateway');
        }

        return $this->gateway;
    }

}