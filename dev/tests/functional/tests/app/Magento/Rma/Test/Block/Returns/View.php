<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Returns;

use Magento\Rma\Test\Block\Returns\View\RmaItems;
use Magento\Mtf\Block\Block;

/**
 * Rma view block on frontend.
 */
class View extends Block
{
    /**
     * Locator for rma items table.
     *
     * @var string
     */
    protected $rmaItems = '.block-returns-items';

    /**
     * Get rma items.
     *
     * @return RmaItems
     */
    public function getRmaItems()
    {
        return $this->blockFactory->create(
            \Magento\Rma\Test\Block\Returns\View\RmaItems::class,
            ['element' => $this->_rootElement->find($this->rmaItems)]
        );
    }
}
