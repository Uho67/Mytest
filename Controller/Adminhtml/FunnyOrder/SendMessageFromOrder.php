<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-21
 * Time: 13:04
 */

namespace Vaimo\Mytest\Controller\Adminhtml\FunnyOrder;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Controller\ResultFactory;;

class SendMessageFromOrder extends Action
{
    private $transportBuilder;
    private $storeManager;
    private $logger;
    public function __construct(
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        Context $context
    ) {
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        parent::__construct($context);
    }

    public function execute()
    {
       $dataMessage = $this->getRequest()->getParams();
        $receiverInfo = [
            'name' => $dataMessage['first_name'].' '.$dataMessage['last_name'],
            'email' => $dataMessage['email']
        ];
        $templateParams = [
            'orderId' => $dataMessage['funny_id'],
            'name' => $dataMessage['first_name'].' '.$dataMessage['last_name'],
            'message' => $dataMessage['message']
        ];
        $transport = $this->transportBuilder->setTemplateIdentifier(
            'funny_order_admin_message_template'
        )->setTemplateVars(
            $templateParams
        )->setTemplateOptions(
            [
                'area' => 'frontend',
                'store' => $this->storeManager->getStore()->getId()
            ]
        )->addTo(
            $receiverInfo['email'], $receiverInfo['name']
        )->getTransport();
        try {
            $transport->sendMessage();
            $this->messageManager->addSuccessMessage(__('Message was sent'));
            return $this->_redirect('*/*/listing');
        } catch (\Exception $e) {
            $this->messageManager->addWarningMessage($e->getMessage());
            $this->logger->critical($e->getMessage());
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
    }
}
