<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogPermissions\Model\Plugin\Theme\Block\Html;

use Magento\Customer\Model\Session;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\Interception\PluginList;
use Magento\Theme\Block\Html\Topmenu as TopmenuBlock;

/**
 * Class TopmenuTest.
 *
 * @package Magento\CatalogPermissions\Model\Permission
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class TopmenuTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Make sure plugin is registered.
     */
    public function testPluginIsRegistered()
    {
        /** @var PluginList $pluginList */
        $pluginList = Bootstrap::getObjectManager()->create(PluginList::class);
        /** @var array $pluginInfo */
        $pluginInfo = $pluginList->get(TopmenuBlock::class, []);
        $this->assertSame(Topmenu::class, $pluginInfo['catalogPermissionsCachingTopmenu']['instance']);
    }

    /**
     * Check Topmenu plugin generates unique cache keys for different customer groups.
     *
     * @magentoDataFixture Magento/CatalogPermissions/_files/enable_permissions_for_specific_customer_group.php
     * @magentoAppArea frontend
     */
    public function testPluginChancheCacheKey()
    {
        /** @var Session $customerSession */
        $customerSession = Bootstrap::getObjectManager()->get(Session::class);
        /** @var TopmenuBlock $topMenu */
        $topMenu = Bootstrap::getObjectManager()->get(TopmenuBlock::class);

        $customerSession->setCustomerGroupId(1);
        $topMenu->toHtml();
        $cacheKeyForFirstCustomerGroup = $topMenu->getCacheKey();

        $customerSession->setCustomerGroupId(2);
        $topMenu->toHtml();
        $cacheKeyForSecondCustomerGroup = $topMenu->getCacheKey();

        self::assertNotEquals($cacheKeyForFirstCustomerGroup, $cacheKeyForSecondCustomerGroup);
    }
}
