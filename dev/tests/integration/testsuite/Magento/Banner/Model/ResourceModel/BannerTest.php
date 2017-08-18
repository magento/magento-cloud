<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Banner\Model\ResourceModel;

class BannerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Banner\Model\ResourceModel\Banner
     */
    private $_resourceModel;

    /**
     * @var int
     */
    protected $_websiteId = 1;

    /**
     * @var int
     */
    protected $_customerGroupId = \Magento\Customer\Model\GroupManagement::NOT_LOGGED_IN_ID;

    protected function setUp()
    {
        $this->_resourceModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Banner\Model\ResourceModel\Banner::class
        );
    }

    protected function tearDown()
    {
        $this->_resourceModel = null;
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     * @magentoDataFixture Magento/CatalogRule/_files/catalog_rule_10_off_not_logged.php
     * @magentoDataFixture Magento/Banner/_files/banner.php
     */
    public function testGetCatalogRuleRelatedBannerIdsNoBannerConnected()
    {
        $this->assertEmpty(
            $this->_resourceModel->getCatalogRuleRelatedBannerIds($this->_websiteId, $this->_customerGroupId)
        );
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     * @magentoDataFixture Magento/Banner/_files/banner_catalog_rule.php
     */
    public function testGetCatalogRuleRelatedBannerIds()
    {
        $banner = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Banner\Model\Banner::class
        );
        $banner->load('Test Banner', 'name');

        $this->assertSame(
            [$banner->getId()],
            $this->_resourceModel->getCatalogRuleRelatedBannerIds($this->_websiteId, $this->_customerGroupId)
        );
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     * @magentoDataFixture Magento/Banner/_files/banner_catalog_rule.php
     * @dataProvider getCatalogRuleRelatedBannerIdsWrongDataDataProvider
     */
    public function testGetCatalogRuleRelatedBannerIdsWrongData($websiteId, $customerGroupId)
    {
        $this->assertEmpty($this->_resourceModel->getCatalogRuleRelatedBannerIds($websiteId, $customerGroupId));
    }

    /**
     * @return array
     */
    public function getCatalogRuleRelatedBannerIdsWrongDataDataProvider()
    {
        return [
            'wrong website' => [$this->_websiteId + 1, $this->_customerGroupId],
            'wrong customer group' => [$this->_websiteId, $this->_customerGroupId + 1]
        ];
    }

    /**
     * @magentoDataFixture Magento/Banner/_files/banner_disabled_40_percent_off.php
     * @magentoDataFixture Magento/Banner/_files/banner_enabled_40_to_50_percent_off.php
     */
    public function testGetSalesRuleRelatedBannerIds()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $registry = $objectManager->get(\Magento\Framework\Registry::class);
        $ruleId = $registry->registry('Magento/SalesRule/_files/cart_rule_40_percent_off');

        /** @var \Magento\Banner\Model\Banner $banner */
        $banner = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Banner\Model\Banner::class
        );
        $banner->load('Get from 40% to 50% Off on Large Orders', 'name');

        $this->assertEquals(
            [$banner->getId()],
            $this->_resourceModel->getSalesRuleRelatedBannerIds([$ruleId])
        );
    }

    /**
     * @magentoDataFixture Magento/Banner/_files/banner_enabled_40_to_50_percent_off.php
     * @magentoDataFixture Magento/Banner/_files/banner_disabled_40_percent_off.php
     */
    public function testGetSalesRuleRelatedBannerIdsNoRules()
    {
        $this->assertEmpty($this->_resourceModel->getSalesRuleRelatedBannerIds([]));
    }
}
