<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
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
     * @param \Magento\Mtf\Fixture\FixtureInterface $fixture
     * @return SimpleElement
     */
    public function getProduct($fixture)
    {
        return $this->getRow(['name' => $fixture->getData('name')]);
    }

    /**
     * @param SimpleElement $row
     */
    public function deleteProduct($row)
    {
        $row->find($this->unassignButton)->click();
        $this->waitLoader();
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
