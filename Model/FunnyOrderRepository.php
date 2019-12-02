<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-04
 * Time: 09:04
 */

namespace Vaimo\Mytest\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Vaimo\Mytest\Api\FunnyOrderRepositoryInterface;
use Vaimo\Mytest\Model\ResourceModel\FunnyOrder\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\Search\SearchResultInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Vaimo\Mytest\Model\ResourceModel\FunnyOrder as ResourceModel;
use Magento\Framework\Event\ManagerInterface as EventManager;

/**
 * Class FunnyOrderRepository
 * @package Vaimo\Mytest\Model
 */
class FunnyOrderRepository implements FunnyOrderRepositoryInterface
{
    /**
     * @var SearchResultInterfaceFactory
     */
    private $searchResultFactory;
    /**
     * @var FunnyOrderFactory
     */
    private $funnyOrderFactory;
    /**
     * @var ResourceModel
     */
    private $resourceModel;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * FunnyOrderRepository constructor.
     *
     * @param EventManager $eventManager
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param SearchResultInterfaceFactory $searchResultInterfaceFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $collectionFactory
     * @param ResourceModel $resourceModel
     * @param \Vaimo\Mytest\Model\FunnyOrderFactory $funnyOrderFactory
     */
    public function __construct(
        EventManager $eventManager,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        SearchResultInterfaceFactory $searchResultInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $collectionFactory,
        ResourceModel $resourceModel,
        FunnyOrderFactory $funnyOrderFactory
    ) {
        $this->eventManager = $eventManager;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->searchResultFactory = $searchResultInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
        $this->resourceModel = $resourceModel;
        $this->funnyOrderFactory = $funnyOrderFactory;
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $funnyOrder = $this->funnyOrderFactory->create();
        $this->resourceModel->load($funnyOrder, $id);
        if (!$funnyOrder->getId()) {
            throw new NoSuchEntityException(__('Order with id "%1" does not exist.', $id));
        } else {
            return $funnyOrder;
        }
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return mixed
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * @param $id
     *
     * @return mixed|void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($id)
    {
        try {
            $this->delete($this->getById($id));
        } catch (NoSuchEntityException $e) {
        }
    }

    /**
     * @param FunnyOrderInterface $model
     *
     * @return $this
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(FunnyOrderInterface $model)
    {
        try {
            $this->resourceModel->delete($model);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(__($exception->getMessage()));
        }

        return $this;
    }

    /**
     * @param FunnyOrderInterface $model
     *
     * @return FunnyOrderInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(FunnyOrderInterface $model)
    {
        try {
            $this->resourceModel->validationTime($model);
            if ($model->getId()) {
                $oldModel = $this->getById($model->getId());
                $diff = $oldModel->getStatus() - $model->getStatus();
            } else {
                $diff = 1;
            }
            $this->resourceModel->save($model);
            if ($diff !== 0) {
                if (!$model->getStatus()) {
                    $model->setStatus(1);
                }
                $this->eventManager->dispatch('vaimo_funny_order_model_status_was_changed', ['orderModel' => $model]);
            }
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__($exception->getMessage()));
        }

        return $model;
    }
}
