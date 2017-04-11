<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Amqp;

use Magento\TestFramework\TestCase\WebapiAbstract;

class RemoteServiceCommunicationTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/TestModuleSynchronousAmqp/';
    const OPERATION_REMOTE_SERVICE = 'remoteService';
    const CONSUMER_NAME = 'RemoteServiceTestConsumer';

    protected function setUp()
    {
        /** @var \Magento\Framework\OsInfo $osInfo */
        $osInfo = \Magento\TestFramework\ObjectManager::getInstance()->get('Magento\Framework\OsInfo');
        if ($osInfo->isWindows()) {
            $this->markTestSkipped("This test relies on *nix shell and should be skipped in Windows environment.");
        }
        parent::setUp();
        if (!$this->getConsumerProcessIds()) {
            exec("{$this->getConsumerStartCommand()} > /dev/null &");
        }
    }

    protected function tearDown()
    {
        parent::tearDown();
        foreach ($this->getConsumerProcessIds() as $pid) {
            exec("kill {$pid}");
        }
    }

    public function testRemoteServiceCommunication()
    {
        $this->_markTestAsRestOnly("It is enough to test RPC communication mechanism using just REST tests.");
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . self::OPERATION_REMOTE_SERVICE,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ]
        ];
        $input = 'Input value';
        $response = $this->_webApiCall($serviceInfo, ['simpleDataItem' => $input]);
        $this->assertEquals($input . ' processed by RPC handler', $response);
    }

    /**
     * @return string[]
     */
    protected function getConsumerProcessIds()
    {
        exec("ps ax | grep -v grep | grep '{$this->getConsumerStartCommand()}' | awk '{print $1}'", $output);
        return $output;
    }

    /**
     * @return string
     */
    protected function getConsumerStartCommand()
    {
        $magentoCli = BP . '/bin/magento';
        return "php {$magentoCli} queue:consumers:start -vvv " . self::CONSUMER_NAME;
    }
}
