<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-04
 * Time: 15:11
 */

namespace Vaimo\Mytest\Controller\Adminhtml\FunnyOrder;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Vaimo\Mytest\Api\FunnyOrderRepositoryInterface;
use Vaimo\Mytest\Model\FunnyOrderFactory;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;

use Vaimo\Mytest\Model\FunnyOrderInterface;

class InlineEdit extends AbstractFunnyOrder
{
    private $jsonFactory;
    private $searchBuilderFactory;
    private $filterBuilder;
    private $filterGroupBuilder;
    public function __construct(FilterGroupBuilder $filterGroupBuilder,
                                FilterBuilder $filterBuilder,
                                SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
                                JsonFactory $jsonFactory,
                                FunnyOrderFactory $fannyOrderFactory,
                                PageFactory $pageFactory,
                                FunnyOrderRepositoryInterface $orderRepository,
                                Context $context
    ) {
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->searchBuilderFactory = $searchCriteriaBuilderFactory;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($fannyOrderFactory, $pageFactory, $orderRepository, $context);
    }

    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {

                foreach (array_keys($postItems) as $funnyOrderId) {
                    $order = $this->repository->getById($funnyOrderId);
                    try {
                        $r = $this->isAllowedTime($order);
                        $order->setData(array_merge($order->getData(), $postItems[$funnyOrderId]));
                        $this->repository->save($order);
                    } catch (\Exception $e) {
                        $messages[] = __($e->getMessage());
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
    public function isAllowedTime(\Vaimo\Mytest\Model\FunnyOrderInterface $model)
    {
        $r = $model->getData();
        $filterLess1 = $this->filterBuilder
            ->setField(FunnyOrderInterface::FIELD_FUN_START)
            ->setValue($model->getFunStart())
            ->setConditionType('lt')
            ->create();

        $filterGroupeMin = $this->filterGroupBuilder->setFilters([$filterLess1])->create();



        $searchCriteriaBuilder = $this->searchBuilderFactory->create();
        $searchCriteria = $searchCriteriaBuilder->setFilterGroups([$filterGroupeMin])->create();
        $list = $this->repository->getList($searchCriteria)->getItems();;
        return $list;
    }
}