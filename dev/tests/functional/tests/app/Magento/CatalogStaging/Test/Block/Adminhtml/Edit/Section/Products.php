<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Block\Adminhtml\Edit\Section;

use Magento\Ui\Test\Block\Adminhtml\Section;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Products section on update edit page.
 */
class Products extends Section
{
    /**
     * Xpath locator for products grid.
     *
     * @var string
     */
    private $productsGrid = '//div[contains(@class, "staging_update_edit_product_grid")]';

    /**
     * Get products grid.
     *
     * @param SimpleElement|null $element
     * @return \Magento\CatalogStaging\Test\Block\Adminhtml\Edit\Section\Products\Grid
     */
    public function getProductsGrid(SimpleElement $element = null)
    {
        $element = $element ?: $this->browser->find($this->productsGrid, Locator::SELECTOR_XPATH);
        return $this->blockFactory->create(
            \Magento\CatalogStaging\Test\Block\Adminhtml\Edit\Section\Products\Grid::class,
            ['element' => $element]
        );
    }
}
