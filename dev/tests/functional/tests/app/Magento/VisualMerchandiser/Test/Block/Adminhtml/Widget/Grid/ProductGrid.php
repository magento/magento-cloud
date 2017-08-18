<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid;

use Magento\Backend\Test\Block\Widget\Grid;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * VisualMerchandiser products grid of Category Products tab.
 */
class ProductGrid extends Grid
{
    /**
     * An element locator which allows to select SKU in grid.
     *
     * @var string
     */
    protected $sku = '.col-sku';

    /**
     * An element locator which allows to select entities in grid.
     *
     * @var string
     */
    protected $selectItem = 'tbody tr .col-in_category';

    /**
     * Selector for Unassign button.
     *
     * @var string
     */
    protected $unassignButton = '.col-action a';

    /**
     * @param int $index
     * @return SimpleElement
     */
    public function getItemByIndex($index)
    {
        return $this->_rootElement->find("tbody > tr:nth-child({$index})");
    }

    /**
     * @param int $index
     * @return string
     */
    public function getSkuByIndex($index)
    {
        $src = $this->getItemByIndex($index);
        return $src->find($this->sku)->getText();
    }

    /**
     * Drag and drop grid item from initial to target position
     *
     * @param int $initial
     * @param int $target
     * @return void
     */
    public function dragAndDrop($initial, $target)
    {
        $this->_rootElement->find($this->actionNextPage)->hover();
        $this->getItemByIndex($initial)->dragAndDrop($this->getItemByIndex($target));
    }

    /**
     * @param \Magento\Mtf\Fixture\FixtureInterface $fixture
     * @return SimpleElement
     */
    public function getProduct($fixture)
    {
        return $this->getRow(['name' => $fixture->getData('name')]);
    }

    /**
     * @param SimpleElement $row
     * @return $this
     */
    public function deleteProduct($row)
    {
        $row->find($this->unassignButton)->click();
        $this->waitLoader();
        return $this;
    }

    /**
     * @param \Magento\Mtf\Fixture\FixtureInterface $fixture
     * @return bool
     */
    public function isProductVisible($fixture)
    {
        return $this->isRowVisible(['name' => $fixture->getData('name')], false);
    }

    /**
     * {@inheritdoc}
     */
    public function waitLoader()
    {
        parent::waitLoader();
    }
}
