<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab\Items;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Grid create rma items.
 */
class Grid extends \Magento\Backend\Test\Block\Widget\Grid
{
    /**
     * Locator item row by name.
     *
     * @var string
     */
    protected $rowByName = './/tbody/tr[./td[contains(@class,"col-product_name") and contains(text(),"%s")]]';

    /**
     * Get item row.
     *
     * @param FixtureInterface $product
     * @return SimpleElement
     */
    public function getItemRow(FixtureInterface $product)
    {
        return $this->_rootElement->find(sprintf($this->rowByName, $product->getName()), Locator::SELECTOR_XPATH);
    }
}
