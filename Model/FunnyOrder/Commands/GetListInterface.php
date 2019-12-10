<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-03
 * Time: 10:20
 */

namespace Vaimo\Mytest\Model\FunnyOrder\Commands;

use Magento\Framework\Exception\NotFoundException;
/**
 * Interface GetListInterface
 * @package Vaimo\Mytest\Model\FunnyOrder\Commands
 */
interface GetListInterface
{
    /**
     * @param string[] $rules
     *
     * @return array
     * @throws NotFoundException
     */
    public function execute(array $rules);
}
//testcontroller for this interface Controller\Test\TestGetListCommand
