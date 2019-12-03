<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-03
 * Time: 10:21
 */

namespace Vaimo\Mytest\Model\FunnyOrder\Commands;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\NotFoundException;
use Vaimo\Mytest\Model\FunnyOrderInterface;

/**
 * Class GetList
 * @package Vaimo\Mytest\Model\FunnyOrder\Commands
 */
class GetList implements GetListInterface
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * GetList constructor.
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param string[] $rules
     *
     * @return array
     * @throws NotFoundException
     */
    public function execute(array $rules)
    {

        if (empty($rules)) {
            $connection = $this->resourceConnection->getConnection();
            $tableName = $connection->getTableName(FunnyOrderInterface::TABLE_NAME);
            $select = $connection->select()->from($tableName);
        } else {
            $errorFields = array_diff(array_keys($rules), [
                FunnyOrderInterface::FIELD_ID,
                FunnyOrderInterface::FIELD_CREATE_ORDER,
                FunnyOrderInterface::FIELD_FUN_START,
                FunnyOrderInterface::FIELD_FUN_END,
                FunnyOrderInterface::FIELD_PHONE,
                FunnyOrderInterface::FIELD_STATUS,
                FunnyOrderInterface::FIELD_CUSTOMER_ID,
                FunnyOrderInterface::FIELD_WISH
            ]);
            if ($errorFields) {
                $errorFields = implode(',', $errorFields);
                throw new NotFoundException(__("Fields $errorFields not found"));
            } else {
                $connection = $this->resourceConnection->getConnection();
                $tableName = $connection->getTableName(FunnyOrderInterface::TABLE_NAME);
                $select = $connection->select()->from($tableName);
                foreach ($rules as $key => $rule) {
                    $values = $this->parseStringForSql($rule);
                    $select->where($key . $values['math'] . '(?)', $values['values']);
                }
            }
        }
        $result = $connection->fetchAll($select);

        return $result;
    }

    /**
     * @param $str
     *
     * @return array
     */
    private function parseStringForSql($str)
    {
        $actionArr = [
            'in',
            'not in'
        ];
        $response = array();
        $str = explode(',', $str);
        $math = trim(array_shift($str));
        $str = array_map('trim', $str);
        if (in_array($math, $actionArr) === true) {
            $response['math'] = ' ' . $math . ' ';
            $response['values'] = $str;

            return $response;
        }
        $response['math'] = ' ' . $math;
        $response['values'] = $str[0];

        return $response;
    }
}
