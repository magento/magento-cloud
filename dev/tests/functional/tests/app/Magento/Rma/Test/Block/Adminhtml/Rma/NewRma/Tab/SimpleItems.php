<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab;

/**
 * Rma items tab for simple product.
 */
class SimpleItems extends Items
{
    /**
     * Fill item product in rma items grid.
     *
     * @param array $itemData
     * @return void
     */
    protected function fillItem(array $itemData)
    {
        /** @var \Magento\Catalog\Test\Fixture\CatalogProductSimple $product */
        $product = $itemData['product'];
        unset($itemData['product']);
        $itemRow = $this->getItemsGrid()->getItemRow($product);
        $itemData = $this->fillDetailsForm($itemData, $itemRow);
        $fields = $this->dataMapping($itemData);
        $this->_fill($fields, $itemRow);
    }
}
