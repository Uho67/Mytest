<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-01
 * Time: 18:37
 */

namespace Vaimo\Mytest\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Vaimo\Mytest\Model\FunnyOrderInterface;
use Magento\Framework\Exception\CouldNotSaveException;


/**
 * Class FunnyOrder
 * @package Vaimo\Mytest\Model\ResourceModel
 */
class FunnyOrder extends AbstractDb
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init(FunnyOrderInterface::TABLE_NAME, FunnyOrderInterface::FIELD_ID);
    }

    /**
     * @param FunnyOrderInterface $object
     *
     * @return bool
     * @throws CouldNotSaveException
     */
    public function validationTime(FunnyOrderInterface $object)
    {
        try {
            $object->formatterData();
            if($object->getFunEnd() <= $object->getFunStart()) {
                throw new CouldNotSaveException(__('Finish time must be more than start'));
            }
            $connection = $this->getConnection();
            $tableName = $connection->getTableName(FunnyOrderInterface::TABLE_NAME);
            $select = $connection->select()->from($tableName)
                ->where(FunnyOrderInterface::FIELD_FUN_START . '>?', $object->getFunEnd())
                ->where(FunnyOrderInterface::FIELD_FUN_END . '>?', $object->getFunEnd())
                ->orWhere(FunnyOrderInterface::FIELD_FUN_START . '<?', $object->getFunStart())
                ->where(FunnyOrderInterface::FIELD_FUN_END . '<?', $object->getFunStart());
            $selectResult = $connection->select()->from($select, 'COUNT(*) as count')
                ->where(FunnyOrderInterface::FIELD_ID . '!=?', $object->getId())
                ->where(FunnyOrderInterface::FIELD_STATUS . '=?', FunnyOrderInterface::AVALIBLE_STATUS);
            $result = $connection->fetchRow($selectResult);
            $generalCount = $connection->fetchRow($connection->select()->from($tableName, 'COUNT(*) as count')
                ->where(FunnyOrderInterface::FIELD_ID . '!=?', $object->getId())
                ->where(FunnyOrderInterface::FIELD_STATUS . '=?', FunnyOrderInterface::AVALIBLE_STATUS));
            if($result['count'] == $generalCount['count']) {
                return true;
            } else {
                throw new CouldNotSaveException(__('Chosen time is busy'));
            }
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     *
     * @return AbstractDb
     * @throws CouldNotSaveException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        try{
            $this->validationTime($object);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return parent::_beforeSave($object);
    }
}
