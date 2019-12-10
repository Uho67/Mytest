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
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class TestGetListCommand
 * @package Vaimo\Mytest\Controller\Test
 */
class TestGetListCommand extends Action
{
    /**
     * @var Curl
     */
    private $curl;
    private $json;

    /**
     * TestGetListCommand constructor.
     *
     * @param Json $json
     * @param Curl $curl
     * @param Context $context
     */
    public function __construct(
        Json $json,
        Curl $curl,
        Context $context
    ) {
        $this->json = $json;
        $this->curl = $curl;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $url = "http://devbox.vaimo.test/newmagento/rest/V1/funnyorder/getListCommand?XDEBUG_SESSION_START=netbeans-xdebug";
        $param = $this->json->serialize(['rules'=>['wish'=>' LIKE ,mmm','funny_id'=>'=,175']]);

        $this->curl->setHeaders([
            'Cache-Control' => 'no-cache',
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ]);

        $this->curl->post($url,$param);
        $request = $this->curl->getBody();
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
// for request key = field , first element math operand , next value