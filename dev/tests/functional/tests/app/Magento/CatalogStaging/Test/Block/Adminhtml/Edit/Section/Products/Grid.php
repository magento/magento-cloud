<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Block\Adminhtml\Edit\Section\Products;

use Magento\Mtf\Client\Locator;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Ui\Test\Block\Adminhtml\DataGrid;

/**
 * Products grid on edit update page.
 */
class Grid extends DataGrid
{
    /**
     * Product row Xpath selector.
     *
     * @var string
     */
    private $productName = '//div[@class="data-grid-cell-content" and text()="%s"]';

    /**
     * Selected product link Xpath selector.
     *
     * @var string
     */
    private $selectProductLink = '//tr[td/*[contains(text(), "%s")]]//button';

    /**
     * View/edit product link Xpath selector.
     *
     * @var string
     */
    private $editProductLink = '//a[@data-action="item-edit"]';

    /**
     * Grid column Xpath locator.
     *
     * @var string
     */
    private $column = '//td[count(//span[text()="%s"]/parent::th/preceding-sibling::th)+1]/div';

    /**
     * Check if product name is visible.
     *
     * @param CatalogProductSimple $product
     * @return bool
     */
    public function isProductVisible(CatalogProductSimple $product)
    {
        return $this->_rootElement->find(sprintf($this->productName, $product->getName()), Locator::SELECTOR_XPATH)
            ->isVisible();
    }

    /**
     * Click view/edit product update.
     *
     * @param CatalogProductSimple $product
     * @return void
     */
    public function clickEditProductLink(CatalogProductSimple $product)
    {
        $this->_rootElement->find(sprintf($this->selectProductLink, $product->getName()), Locator::SELECTOR_XPATH)
            ->click();
        $this->_rootElement->find(sprintf($this->editProductLink, $product->getName()), Locator::SELECTOR_XPATH)
            ->click();
    }

    /**
     * Get column values array.
     *
     * @param string $columnName
     * @return array
     */
    public function getColumnValues($columnName)
    {
        $rows = $this->_rootElement->getElements(sprintf($this->column, $columnName), Locator::SELECTOR_XPATH);
        $values = [];
        foreach ($rows as $row) {
            $values[] = $row->getText();
        }
        return $values;
    }
}
