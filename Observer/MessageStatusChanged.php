<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-18
 * Time: 17:20
 */

namespace Vaimo\Mytest\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Customer\Model\SessionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Vaimo\Mytest\Model\Source\StatusFactory;

/**
 * Class MessageStatusChanged
 * @package Vaimo\Mytest\Observer
 */
class MessageStatusChanged implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var TransportBuilder
     */
    private $transportBuilder;
    /**
     * @var SessionFactory
     */
    private $sessionFactory;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var StatusFactory
     */
    private $statusFactory;

    /**
     * MessageStatusChanged constructor.
     *
     * @param StatusFactory $statusFactory
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $transportBuilder
     * @param SessionFactory $sessionFactory
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        StatusFactory $statusFactory,
        LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        SessionFactory $sessionFactory,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->statusFactory = $statusFactory;
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->sessionFactory = $sessionFactory;
    }

    /**
     * @param Observer $observer
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $model = $observer->getData('orderModel');
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
            'newStatus' => $this->statusFactory->create()->toArray()[$model->getStatus()],
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
