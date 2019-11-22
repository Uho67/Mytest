<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-21
 * Time: 14:35
 */

namespace Vaimo\Mytest\Block\Adminhtml\Buttons\Funnyorder;

use Vaimo\Mytest\Block\Adminhtml\Buttons\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SendMessageFromOrder extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Send message'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'SendMessageFromOrder']],
                'form-role' => 'SendMessageFromOrder',
            ],
            'sort_order' => 90,
        ];
    }
}
