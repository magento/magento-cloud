<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedPricingImportExport\Model\Import;

/**
 * @magentoAppArea adminhtml
 */
class AdvancedPricingStagingTest extends AdvancedPricingTest
{
    /**
     * @magentoDataFixture Magento/AdvancedPricingImportExport/_files/create_products.php
     * @magentoAppArea adminhtml
     */
    public function testImportAddUpdate()
    {
        $this->objectManager->get(\Magento\CatalogImportExport\Model\Version::class)->create(
            [
                'AdvancedPricingSimple 1',
                'AdvancedPricingSimple 2'
            ]
        );
        parent::testImportAddUpdate();
    }

    /**
     * @magentoAppArea adminhtml
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testImportDelete()
    {
        $this->objectManager->get(\Magento\CatalogImportExport\Model\Version::class)->create(['simple']);
        parent::testImportDelete();
    }

    /**
     * @magentoDataFixture Magento/AdvancedPricingImportExport/_files/create_products.php
     * @magentoAppArea adminhtml
     */
    public function testImportReplace()
    {
        $this->objectManager->get(\Magento\CatalogImportExport\Model\Version::class)->create(
            [
                'AdvancedPricingSimple 1',
                'AdvancedPricingSimple 2'
            ]
        );
        parent::testImportReplace();
    }
}
