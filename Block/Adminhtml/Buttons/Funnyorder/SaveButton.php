<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-05
 * Time: 10:01
 */

namespace Vaimo\Mytest\Block\Adminhtml\Buttons\Funnyorder;

use Vaimo\Mytest\Block\Adminhtml\Buttons\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveButton
 * @package Vaimo\Mytest\Block\Adminhtml\Buttons\Funnyorder
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Order'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
