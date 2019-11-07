<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-05
 * Time: 09:49
 */

namespace Vaimo\Mytest\Model\Source;

use Magento\Framework\Option\ArrayInterface;

class Status implements ArrayInterface
{
    public function toOptionArray()
    {
        return [['value' => 0, 'label' => __('Rejected')], ['value' => 1, 'label' => __('Accepted')],
                ['value' => 2, 'label' => __('Completed')] ];
    }

    public function toArray()
    {
        return [0 => __('Rejected'), 1 => __('Assepted'), 2 => __('Completed')];
    }
}