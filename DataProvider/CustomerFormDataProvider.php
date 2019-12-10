<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-21
 * Time: 11:41
 */

namespace Vaimo\Mytest\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\Session\SessionManagerInterface;
use Vaimo\Mytest\Model\ResourceModel\FunnyOrder\CollectionFactory;

/**
 * Class CustomerFormDataProvider
 * @package Vaimo\Mytest\DataProvider
 */
class CustomerFormDataProvider extends AbstractDataProvider
{
    private $sessionManager;

    /**
     * CustomerFormDataProvider constructor.
     *
     * @param SessionManagerInterface $sessionManager
     * @param CollectionFactory $collectionFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        SessionManagerInterface $sessionManager,
        CollectionFactory $collectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->sessionManager = $sessionManager;
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        $customer = $this->sessionManager->getCurrentCustomerForFormMessage();
        $this->sessionManager->setCurrentCustomerForFormMessage(null);
        $orderModels = $this->collection->getItems();
        if ($customer != null) {
            foreach ($orderModels as $orderModel) {
                $orderModel->setEmail($customer->getEmail());
                $orderModel->setFirstName($customer->getFirstname());
                $orderModel->setLastName($customer->getLastname());
                return [$orderModel->getId() => $orderModel->getData()];
            }
        } else {
            return [];
        }
    }
}
