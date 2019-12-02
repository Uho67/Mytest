<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-28
 * Time: 20:27
 */

namespace Vaimo\Mytest\Model\FunnyOrder\Commands;

use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Vaimo\Mytest\Model\FunnyOrderInterface;
use Vaimo\Mytest\Model\ResourceModel\FunnyOrder as ResourceModel;

class Save implements SaveInterface
{
    private $resourceModel;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Save constructor.
     *
     * @param ResourceModel $resourceModel
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResourceModel $resourceModel,
        LoggerInterface $logger
    ) {
        $this->resourceModel = $resourceModel;
        $this->logger = $logger;
    }

    /**
     * @param FunnyOrderInterface $order
     *
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(FunnyOrderInterface $order): int
    {
        try {
            /** @var \Magento\Framework\DB\Adapter\Pdo\Mysql $connection */
            $this->resourceModel->validationTime($order);
            $connection = $this->resourceModel->getConnection();
            $tableName = $connection->getTableName(FunnyOrderInterface::TABLE_NAME);
            $data = $order->getData('');
            unset($data['hello']);
            $connection->insertOnDuplicate($tableName, $data);

            return (int)$connection->lastInsertId($tableName);
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
            $message = __('An error occurred during media asset save: %1', $exception->getMessage());
            throw new CouldNotSaveException($message, $exception);
        }
    }
}
//    console command for checkout
//curl -X POST "http://devbox.vaimo.test/newmagento/rest/all/V1/fannyorder" -H "accept: application/json" -H "Content-Type: application/json" -H "Authorization: Bearer 29fe9c14mg7m8wpdh9mgsowf2bob104e" -d "{ \"order\": {\"phone\":\"201901\", \"fun_start\":\"2019-01-01\" }}"