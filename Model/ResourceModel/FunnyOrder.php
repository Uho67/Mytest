<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-01
 * Time: 18:37
 */

namespace Vaimo\Mytest\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Message\ManagerInterface;
use Vaimo\Mytest\Model\FunnyOrderInterface;

class FunnyOrder extends AbstractDb
{
//    private $messageManager;
//    public function __construct(ManagerInterface $messageManager, Context $context, $connectionName = null)
//    {
//        $this->messageManager = $messageManager;
//        parent::__construct($context, $connectionName);
//    }
    protected function _construct()
    {
        $this->_init(FunnyOrderInterface::TABLE_NAME, FunnyOrderInterface::FIELD_ID);
    }
//    public function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
//    {
//        $start = $object->getFunStart();
//        $finish= $object->getFunEnd();
//        $table = $this->getTable(FunnyOrderInterface::TABLE_NAME);
//        $connection = $this->getConnection();
//        $select = $connection->select()->from($table)
//            ->where(FunnyOrderInterface::FIELD_FUN_START.'>?', $finish)
//            ->orWhere(FunnyOrderInterface::FIELD_FUN_START.'<?',$start)
//            ->where(FunnyOrderInterface::FIELD_FUN_END.'>?',$finish)
//            ->orWhere(FunnyOrderInterface::FIELD_FUN_END.'<?',$start);
//        $selectResult = $connection->select()->from($select)
//           ->where(FunnyOrderInterface::FIELD_ID.'!=?',0)
//            ->where(FunnyOrderInterface::FIELD_STATUS.'=?',FunnyOrderInterface::AVALIBLE_STATUS);
//        $newRes = $connection->select()->from($selectResult)
//            ->where(FunnyOrderInterface::FIELD_FUN_START.'>?', $finish)
//            ->where(FunnyOrderInterface::FIELD_FUN_END.'>?',$finish)
//            ->orWhere(FunnyOrderInterface::FIELD_FUN_END.'<?',$start)
//            ->where(FunnyOrderInterface::FIELD_FUN_START.'<?',$start);
//        $result = $connection->fetchAll($newRes);
//        $generalCount = $connection->fetchRow($connection->select()->from($table,'COUNT(*) as count')
//            ->where(FunnyOrderInterface::FIELD_STATUS.'=?',FunnyOrderInterface::AVALIBLE_STATUS));
//        if($object->getId()) {
//            if(count($result) == $generalCount['count']-1) {
//                return parent::_beforeSave($object);
//            } else {
//                $this->messageManager->addErrorMessage(__('Chosen time is nor free'));
//                throw new \Magento\Framework\Exception\CouldNotSaveException(__('Chosen time is nor free'));
//            }
//        } else {
//            if(count($result) == $generalCount['count']) {
//                return parent::_beforeSave($object);
//            } else {
//                $this->messageManager->addErrorMessage(__('Chosen time is nor free'));
//                throw new \Magento\Framework\Exception\CouldNotSaveException(__('Chosen time is nor free'));
//            }
//        }
//        return parent::_beforeSave($object);
//    }
}