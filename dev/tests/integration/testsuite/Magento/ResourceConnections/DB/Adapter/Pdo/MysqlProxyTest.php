<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ResourceConnections\DB\Adapter\Pdo;

/**
 * @magentoAppIsolation enabled
 * @magentoDbIsolation enabled
 * @backupGlobals enabled
 */
class MysqlProxyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $configModel;

    /**
     * @var array
     */
    protected $configArray;
    
    /**
     * @return void
     */
    protected function setUp()
    {
        $config = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\App\DeploymentConfig'
        );
        $this->configArray = $config->getConfigData();

        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    /**
     * @param array $slaveConfig
     * @return void
     */
    protected function updateSlaveConfig($slaveConfig)
    {
        $writer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\App\DeploymentConfig\Writer'
        );
        $writer->saveConfig(
            ['app_env' =>
                ['db' =>
                    ['connection' =>
                        ['default' =>
                            ['slave' =>
                                $slaveConfig
                            ]
                        ]
                    ]
                ]
            ]
        );

        $reader = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            '\Magento\Framework\App\DeploymentConfig\Reader'
        );

        $deploymentConfig = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            '\Magento\Framework\App\DeploymentConfig',
            ['reader' => $reader]
        );

        $resource = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Framework\App\ResourceConnection',
            ['deploymentConfig' => $deploymentConfig]
        );

        $context = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Framework\Model\ResourceModel\Db\Context',
            ['resource' => $resource]
        );

        $this->configModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Config\Model\ResourceModel\Config',
            ['context' => $context]
        );
    }

    /**
     * @expectedException \Zend_Db_Adapter_Exception
     *
     * @return void
     */
    public function testDBOperationWithNotValidSlaveConfig()
    {
        $slaveConfig = $this->configArray['db']['connection']['default'];
        $slaveConfig['password'] = $slaveConfig['password'] . '_not_valid';
        $this->updateSlaveConfig($slaveConfig);

        $connection = $this->configModel->getConnection();
        $this->assertInstanceOf('Magento\ResourceConnections\DB\Adapter\Pdo\MysqlProxy', $connection);
        $connection->select()->from($this->configModel->getMainTable())->where('path=?', 'test/config');
    }

    /**
     * @return void
     */
    public function testDBOperationWithValidSlaveConfig()
    {
        $slaveConfig = $this->configArray['db']['connection']['default'];
        $this->updateSlaveConfig($slaveConfig);

        $connection = $this->configModel->getConnection();
        $this->assertInstanceOf('Magento\ResourceConnections\DB\Adapter\Pdo\MysqlProxy', $connection);
        $select = $connection->select()->from($this->configModel->getMainTable())->where('path=?', 'test/config');
        $this->configModel->saveConfig('test/config', 'test', 'default', 0);
        $this->assertNotEmpty($connection->fetchRow($select));

        $this->configModel->deleteConfig('test/config', 'default', 0);
        $this->assertEmpty($connection->fetchRow($select));
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        $writer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\App\DeploymentConfig\Writer'
        );
        $writer->saveConfig(['app_env' => ['db' => $this->configArray['db']]], true);
    }
}
