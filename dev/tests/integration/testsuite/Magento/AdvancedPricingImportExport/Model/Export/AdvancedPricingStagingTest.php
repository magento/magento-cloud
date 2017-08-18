<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedPricingImportExport\Model\Export;

class AdvancedPricingStagingTest extends AdvancedPricingTest
{
    /**
     * @magentoAppArea adminhtml
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testExport()
    {
        $this->objectManager->get(\Magento\CatalogImportExport\Model\Version::class)->create(['simple']);
        parent::testExport();
    }
}
