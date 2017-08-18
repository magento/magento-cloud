<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ConfigurableImportExport\Model;

class ConfigurableStagingTest extends ConfigurableTest
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
        $extensionAttributes->setConfigurableProductOptions([]);
        $product->setExtensionAttributes($extensionAttributes);
    }
}
