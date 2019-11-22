<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-20
 * Time: 09:58
 */

namespace Vaimo\Mytest\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class ProductCollectionAfterLoad
 * @package Vaimo\Mytest\Observer
 */
class ProductCollectionAfterLoad implements ObserverInterface
{
    /**
     * @param Observer $observer
     *
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        $collection = $observer->getData('collection');
        foreach ($collection->getItems() as $item) {
            $item->setPrice($item->getPrice() + 100);
        }

        return $this;
    }
}
