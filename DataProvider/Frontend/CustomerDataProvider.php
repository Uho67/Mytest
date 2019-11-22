<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-11
 * Time: 13:52
 */

namespace Vaimo\Mytest\DataProvider\Frontend;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Vaimo\Mytest\Model\ResourceModel\FunnyOrder\CollectionFactory;
use Vaimo\Mytest\Model\FunnyOrderFactory;
use Magento\Customer\Model\SessionFactory;

/**
 * Class CustomerDataProvider
 * @package Vaimo\Mytest\DataProvider\Frontend
 */
class CustomerDataProvider extends AbstractDataProvider
{
    /**
     * @var FunnyOrderFactory
     */
    private $funnyOrderFactory;
    /**
     * @var SessionFactory
     */
    private $sessionFactory;

    /**
     * CustomerDataProvider constructor.
     *
     * @param SessionFactory $sessionFactory
     * @param FunnyOrderFactory $funnyOrderFactory
     * @param CollectionFactory $collectionFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
   public function __construct(SessionFactory $sessionFactory,
                               FunnyOrderFactory $funnyOrderFactory,
                               CollectionFactory $collectionFactory ,
                               $name,
                               $primaryFieldName,
                               $requestFieldName,
                               array $meta = [],
                               array $data = []
   ) {
       $this->sessionFactory    = $sessionFactory;
       $this->funnyOrderFactory = $funnyOrderFactory;
       $this->collection = $collectionFactory->create();
       parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
   }

    /**
     * @return array
     */
   public function getData()
   {
       if ($this->sessionFactory->create()->getCustomer()->getDefaultShippingAddress()) {
           $phone = $this->sessionFactory->create()->getCustomer()->getDefaultShippingAddress()->getTelephone();
           $name  = $this->sessionFactory->create()->getCustomer()->getName();
           $model = $this->funnyOrderFactory->create();
           $model->setPhone($phone);
           $model->setHello($name.' chose please date and time');
           return [$model->getId() => $model->getData()];
       }
       return [null=>['hello'=>'Chose please date and time']];
   }
}
