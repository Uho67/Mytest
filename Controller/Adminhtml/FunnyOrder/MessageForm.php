<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-21
 * Time: 11:32
 */

namespace Vaimo\Mytest\Controller\Adminhtml\FunnyOrder;
use Magento\Backend\App\Action\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\View\Result\PageFactory;
use Vaimo\Mytest\Api\FunnyOrderRepositoryInterface;
use Vaimo\Mytest\Model\FunnyOrderFactory;

class MessageForm extends AbstractFunnyOrder
{
    private $customerRepository;
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        FunnyOrderFactory $fannyOrderFactory,
        PageFactory $pageFactory,
        FunnyOrderRepositoryInterface $orderRepository,
        Context $context
    ) {
        $this->customerRepository = $customerRepository;
        parent::__construct($fannyOrderFactory, $pageFactory, $orderRepository, $context);
    }

    /**
     *
     */
    const TITLE = 'Message to customer';
    /**
     *
     */
    const BREADCRUMB_TITLE = 'Message to customer';

    public function execute()
    {
        $id = $this->getRequest()->getParam(static::QUERY_PARAM_ID);
        if (!empty($id)) {
            $orderModel = $this->repository->getById($id);
            $customerId = $orderModel->getCustomerId();
            try {
                $customer = $this->customerRepository->getById($customerId);
                $this->_getSession()->setCurrentCustomerForFormMessage($customer);
            } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('Customer with id %1 not found', $customerId));
                return $this->redirectToGrid();
            }
        } else {
            $this->messageManager->addErrorMessage(__('Order with id %1 not found', $id));
            return $this->redirectToGrid();
        }

        return parent::execute();
    }
}
