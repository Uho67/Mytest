<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-05
 * Time: 09:30
 */

namespace Vaimo\Mytest\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\Session\SessionManagerInterface;
use Vaimo\Mytest\Model\ResourceModel\FunnyOrder\CollectionFactory;

class FunnyOrderDataProvider extends AbstractDataProvider
{
    private $sessionManager;
    public function __construct(SessionManagerInterface $sessionManager,
                                CollectionFactory $collectionFactory,
                                $name, $primaryFieldName,
                                $requestFieldName,
                                array $meta = [], array $data = [])
    {
        $this->sessionManager = $sessionManager;
        $this->collection     = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        $model = $this->sessionManager->getCurrentFunnyOrderModel();
        $this->sessionManager->setCurrentFunnyOrderModel(null);
        if($model!=null) {
            return [$model->getId()=> $model->getData()];
        } else return [];
    }
}
