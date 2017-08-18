<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Logging\Model;

use Magento\TestFramework\Helper\Bootstrap;

/**
 * @magentoAppArea adminhtml
 */
class ConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Config
     */
    private $config;

    protected function setUp()
    {
        $objectManager = Bootstrap::getObjectManager();
        $this->config = $objectManager->get(\Magento\Logging\Model\Config::class);
    }

    // @codingStandardsIgnoreStart
    /**
     * @magentoConfigFixture current_store admin/magento_logging/actions {"admin_login":1,"adminhtml_permission_users":1}
     * @magentoAppIsolation enabled
     */
    // @codingStandardsIgnoreEnd
    public function testGetSystemConfigValues()
    {
        $expected = [
            'admin_login' => 1,
            'adminhtml_permission_users' => 1,
        ];
        $this->assertEquals($expected, $this->config->getSystemConfigValues());
    }
}
