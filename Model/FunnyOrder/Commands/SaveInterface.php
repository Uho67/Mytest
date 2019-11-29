<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-28
 * Time: 20:21
 */

namespace Vaimo\Mytest\Model\FunnyOrder\Commands;

use Vaimo\Mytest\Model\FunnyOrderInterface;

/**
 * Interface SaveInterface
 * @package Vaimo\Mytest\Model\FunnyOrder\Commands
 *
 */
interface SaveInterface
{
    /**
     * @param FunnyOrderInterface $order
     *
     * @return int
     */
    public function execute(FunnyOrderInterface $order) : int;
}
