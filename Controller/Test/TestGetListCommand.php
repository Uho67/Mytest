<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-03
 * Time: 13:57
 */

namespace Vaimo\Mytest\Controller\Test;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Serialize\Serializer\JsonFactory;

/**
 * Class TestGetListCommand
 * @package Vaimo\Mytest\Controller\Test
 */
class TestGetListCommand extends Action
{
    /**
     * @var CurlFactory
     */
    private $curlFactory;
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * TestGetListCommand constructor.
     *
     * @param JsonFactory $jsonFactory
     * @param CurlFactory $curlFactory
     * @param Context $context
     */
    public function __construct(
        JsonFactory $jsonFactory,
        CurlFactory $curlFactory,
        Context $context
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->curlFactory = $curlFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $url = "http://devbox.vaimo.test/newmagento/rest/V1/funnyorder/getListCommand?XDEBUG_SESSION_START=netbeans-xdebug";
        $param = $this->jsonFactory->create()->serialize(['rules'=>['wish'=>' LIKE ,mmm','funny_id'=>'=,175']]);
        $curl = $this->curlFactory->create();
        $curl->setHeaders([
            'Cache-Control' => 'no-cache',
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ]);

        $curl->post($url,$param);
        $request = $curl->getBody();
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
// for request key = field , first element math operand , next value