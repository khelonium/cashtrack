<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 28/09/14
 * Time: 14:29
 */

namespace Reporter;


use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\TableGateway\Exception\RuntimeException;

class Overview implements AdapterAwareInterface
{

   use AdapterAwareTrait;

    /**
     * @param $id
     * @param $dbAdapter
     * @return mixed
     */
    public function getDetailed($month, $idCategory = null)
    {

        $adapter = $this->getAdapter();
        $and     = '';

        if ($idCategory) {
            $and = " AND id_category = $idCategory ";
        }

        $statement = $adapter->query(
            "select id_category, name ,round(sum(amount)) as total " .
            "from expense_overview   where month like '%$month%' $and group by name"
        );


        return $this->asArray($statement->execute());
    }

    /**
     * Returns transaction overview
     * @param $month in format 2014-9
     * @param array $expandList if specified, main categories will be broken accordingly
     * @return array
     */
    public function getOverview($month, $expandList = [])
    {
        $base = $this->getRegular($month);
        $out  = [];

        foreach ($expandList as $categoryId) {
            $base = array_filter($base, function ($el) use ($categoryId) {
                return $el['id_category'] != $categoryId;
            });
            $out  = array_merge($out, $this->getDetailed($month, $categoryId));
        }


        return array_merge($out, $base);
    }

    /**
     * @param $month
     * @return mixed
     * @internal param $id
     * @internal param $dbAdapter
     */
    public function getRegular($month)
    {

        $statement = $this->getAdapter()->query(
            "select id_category, category  as name,round(sum(amount)) as total " .
            "from expense_overview   where month like '%$month%' group by category"
        );

        return $this->asArray($statement->execute());

    }


    /**
     * @return Adapter
     */
    private function getAdapter()
    {
        if (null === $this->adapter) {
            throw new \RuntimeException("Db Adapter not set");
        }
        return $this->adapter;
    }

    /**
     * @param $result
     * @return array
     */
    private function asArray($result)
    {
        $out = [];

        foreach ($result as $entry) {
            $out[] = $entry;
        }
        return $out;
    }

} 