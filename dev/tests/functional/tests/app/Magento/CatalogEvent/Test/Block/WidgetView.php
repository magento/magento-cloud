<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Block;

use Magento\Mtf\Client\Locator;

/**
 * The block of "Catalog Events Carousel" Widget on Storefront.
 */
class WidgetView extends \Magento\Widget\Test\Block\WidgetView
{
    /**
     * Selector for: link to interested Sale Event inside the Widget.
     *
     * @var string
     */
    protected $interestedEventInsideLister = '//a[contains(.,"%s")]';

    /**
     * Customer opens interested Sale Event by Category name.
     *
     * @param string $categoryName
     * @return void
     */
    public function openInterestedEvent($categoryName)
    {
        $this->_rootElement->find(
            sprintf($this->interestedEventInsideLister, $categoryName),
            Locator::SELECTOR_XPATH
        )->click();
    }
}
