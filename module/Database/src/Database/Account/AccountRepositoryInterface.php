<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 06/10/14
 * Time: 13:00
 */
namespace Database\Account;

use Finance\Account\Account;
use Refactoring\Db\Entity;

interface AccountRepositoryInterface
{
    /**
     * Returns all entities
     * @return array
     */
    public function all();

    /**
     * You are allowed to guess three times what this does
     * @param Entity $entity
     */
    public function add(Entity $entity);

    public function update(Entity $entity);

    public function addFromName($name);

    /**
     * @param $idList
     */
    public function getList(array $idList);

    /**
     * @param $accountName
     * @return Account
     */
    public function addOrLoad($accountName);

    /**
     * @param $type possible values income|expense|buffer|saving
     * @return array
     */
    public function getByType($type);
}