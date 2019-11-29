<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-11-28
 * Time: 20:27
 */

namespace Vaimo\Mytest\Model\FunnyOrder\Commands;

use Vaimo\Mytest\Model\FunnyOrderInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class Save
 * @package Vaimo\Mytest\Model\FunnyOrder\Commands
 */
class Save implements SaveInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * Save constructor.
     *
     * @param LoggerInterface $logger
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        LoggerInterface $logger,
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
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
            $connection = $this->resourceConnection->getConnection();
            $tableName = $this->resourceConnection->getTableName(FunnyOrderInterface::TABLE_NAME);
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
//curl -X POST "http://devbox.vaimo.test/newmagento/rest/all/V1/fannyorder" -H "accept: application/json" -H "Content-Type: application/json" -H "Authorization: Bearer 29fe9c14mg7m8wpdh9mgsowf2bob104e" -d "{ \"order\": {\"phone\":\"201901\", \"fun_start\":\"2019-01-01\" }}"