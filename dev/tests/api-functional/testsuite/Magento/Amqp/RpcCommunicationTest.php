<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Amqp;

use Magento\TestFramework\TestCase\WebapiAbstract;

class RpcCommunicationTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/TestModuleSynchronousAmqp/';
    const OPERATION_SYNC_RPC = 'syncRpc';
    const CONSUMER_NAME = 'synchronousRpcTestConsumer';

    const RPC_CALLS_COUNT = 2;

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

    /**
     * Verify that RPC call based on Rabbit MQ is processed correctly.
     *
     * Current test is not test of Web API framework itself, it just utilizes its infrastructure to test RPC.
     */
    public function testSynchronousRpcCommunication()
    {
        $this->_markTestAsRestOnly("It is enough to test RPC communication mechanism using just REST tests.");
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . self::OPERATION_SYNC_RPC,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ]
        ];
        $input = 'Input value';

        for ($i = 0; $i < self::RPC_CALLS_COUNT; $i++) {
            $response = $this->_webApiCall($serviceInfo, ['simpleDataItem' => $input]);
            $this->assertEquals($input . ' processed by RPC handler', $response);
        }
    }

    /**
     * Negative Flow: Verify if input is not string, exception "Decoding error." is returned.
     * @expectedException \Exception
     * @expectedExceptionMessage {"message":"Invalid type for value: \"array\". Expected Type: \"string\"."}
     */
    public function testInputValidation()
    {
        $this->_markTestAsRestOnly("It is enough to test RPC communication mechanism using just REST tests.");
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . self::OPERATION_SYNC_RPC,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ]
        ];

        $input = [
            "foo" => "bar",
            "Test" => "String",
        ];
        $this->_webApiCall($serviceInfo, ['simpleDataItem' => $input]);
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
        return "php {$magentoCli} queue:consumers:start -vvv " . self::CONSUMER_NAME
            . ' --max-messages=' . self::RPC_CALLS_COUNT;
    }
}
