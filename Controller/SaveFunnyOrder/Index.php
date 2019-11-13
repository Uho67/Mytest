<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-11
 * Time: 11:37
 */

namespace Vaimo\Mytest\Controller\SaveFunnyOrder;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Vaimo\Mytest\Model\FunnyOrderRepository;
use Vaimo\Mytest\Model\FunnyOrderFactory;
use Vaimo\Mytest\Model\FunnyOrderInterface;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{
    private $repository;
    private $orderFactory;

    public function __construct(FunnyOrderFactory $funnyOrderFactory,
                                FunnyOrderRepository $funnyOrderRepository,
                                Context $context)
    {
        $this->repository = $funnyOrderRepository;
        $this->orderFactory = $funnyOrderFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $formData = $this->getRequest()->getParams();
        if(!$this->validation($formData)){
            $this->messageManager->addErrorMessage(__('Check out please data in fields'));
            return $this->redirectToLastPage();
        } else {
            try{
                $this->repository->save($this->orderFactory->create()->setData($formData));
                $this->messageManager->addSuccessMessage(__('Order has been saved.'));

            } catch (\Exception $e){
                if ($e->getMessage()) {
                    $this->messageManager->addWarningMessage($e->getMessage());
                    return $this->redirectToLastPage();
                } else {
                    $this->messageManager->addErrorMessage(__('Order doesn\'t save please try again'));
                    return $this->redirectToLastPage();
                }
            }
        }
    }
     private function validation($formData)
     {
         if(!$formData[FunnyOrderInterface::FIELD_FUN_START] ||
             !$formData[FunnyOrderInterface::FIELD_FUN_END] ||
              !$formData[FunnyOrderInterface::FIELD_PHONE]) {
             return false;
         } else {
             return true;
         }
     }
     private function redirectToLastPage()
     {
         $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
         $resultRedirect->setUrl($this->_redirect->getRefererUrl());
         return $resultRedirect;
     }
}