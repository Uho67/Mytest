<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-19
 * Time: 12:40
 */

namespace Vaimo\Mytest\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class MessageFannyDeleted
 * @package Vaimo\Mytest\Observer
 */
class MessageFannyDeleted implements ObserverInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var TransportBuilder
     */
    private $transportBuilder;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * MessageFannyDeleted constructor.
     *
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $transportBuilder
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->transportBuilder = $transportBuilder;
    }

    /**
     * @param Observer $observer
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $model = $observer->getData('object');
        if ($model->getCustomerId()) {
            $customer = $this->customerRepository->getById($model->getCustomerId());
            $email = $customer->getEmail();
            $name = $customer->getFirstname();
        } else {
            $email = 'uho2898@gmail.com';
            $name = 'Dmitriy Ushchenko';
        }
        $receiverInfo = [
            'name' => $name,
            'email' => $email
        ];
        $templateParams = [
            'orderId' => $model->getId(),
            'name' => $name,
            'newStatus' => 'DELETED'
        ];
        $transport = $this->transportBuilder->setTemplateIdentifier(
            'vaimo_mytest_change_status_funny_order'
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
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
