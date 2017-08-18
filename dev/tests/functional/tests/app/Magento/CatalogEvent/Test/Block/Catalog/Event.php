<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogEvent\Test\Block\Catalog;

use Magento\Mtf\Block\Block;

/**
 * Class Event
 * Event block on the product/category pages
 */
class Event extends Block
{
    /**
     * Event status on the Frontend
     *
     * @var string
     */
    protected $eventStatus = '.block-title';

    /**
     * Get Event Status on the Frontend
     *
     * @return string
     */
    public function getEventStatus()
    {
        return $this->_rootElement->find($this->eventStatus)->getText();
    }
}
