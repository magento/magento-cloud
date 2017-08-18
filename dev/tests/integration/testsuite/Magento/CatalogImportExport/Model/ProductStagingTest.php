<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogImportExport\Model;

/**
 * @magentoDataFixtureBeforeTransaction Magento/Catalog/_files/enable_reindex_schedule.php
 */
class ProductStagingTest extends ProductTest
{
    /**
     * @param array $skus
     */
    protected function modifyData($skus)
    {
        $this->objectManager->get(\Magento\CatalogImportExport\Model\Version::class)->create($skus, $this);
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     */
    public function prepareProduct($product)
    {
    }
}
