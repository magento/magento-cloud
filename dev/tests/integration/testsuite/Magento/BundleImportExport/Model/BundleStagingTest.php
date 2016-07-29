<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\BundleImportExport\Model;

class BundleStagingTest extends BundleTest
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
        $extensionAttributes = $product->getExtensionAttributes();
        $extensionAttributes->setBundleProductOptions([]);
        $product->setExtensionAttributes($extensionAttributes);
    }
}
