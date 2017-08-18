<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\AddProduct;

use Magento\Mtf\Client\Element\SimpleElement;

class DataGrid extends \Magento\Ui\Test\Block\Adminhtml\DataGrid
{
    /**
     * Base part of row locator template for getRow() method.
     *
     * @var string
     */
    protected $rowPattern = './/tbody/tr[%s]';

    /**
     * @var string
     */
    protected $assignSelector = '.data-grid-onoff-cell label';

    /**
     * @var string
     */
    protected $keywordSearchField = '.data-grid-search-control-wrap .data-grid-search-control';

    /**
     * @var string
     */
    protected $keywordSearchSubmit = '.data-grid-search-control-wrap .action-submit';

    /**
     * @param string $keyword
     */
    public function searchByKeyword($keyword)
    {
        $this->resetFilter();
        $this->waitLoader(); // Wait for reset

        $input = $this->_rootElement->find($this->keywordSearchField);
        $input->setValue($keyword);

        $submit = $this->_rootElement->find($this->keywordSearchSubmit);
        $submit->click();

        $this->waitLoader(); // Wait for search
        $this->browser->waitUntil(
            function () {
                $element = $this->_rootElement->find($this->keywordSearchSubmit);
                return $element->isVisible() == true ? true : null;
            }
        );
        $this->waitLoader(); // Wait for grid update
    }

    /**
     * @param array $filter
     * @throws \Exception
     */
    public function searchByNameAndSelect(array $filter)
    {
        $this->searchByKeyword($filter['name']);
        $rowItem = $this->getRow($filter);
        $this->browser->waitUntil(
            function () use ($rowItem) {
                return $rowItem->isVisible() == true ? true : null;
            }
        );
        $this->clickAssign($rowItem);
    }

    /**
     * @param SimpleElement $rowItem
     */
    public function clickAssign(SimpleElement $rowItem)
    {
        $rowItem->find($this->assignSelector)->click();
    }
}
