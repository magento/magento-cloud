<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Block\Adminhtml\Order;

use Magento\Mtf\Client\Locator;
use Magento\AdvancedCheckout\Test\Block\Adminhtml\Order\Create\Items;
use Magento\AdvancedCheckout\Test\Block\Adminhtml\Order\Create\Additional;

/**
 * Adminhtml sales order create block.
 */
class Create extends \Magento\Sales\Test\Block\Adminhtml\Order\Create
{
    /**
     * Sales order additional block.
     *
     * @var string
     */
    protected $orderAdditional = '#order-additional_area';

    /**
     * Getter for order selected products grid.
     *
     * @return Items
     */
    public function getItemsBlock()
    {
        return $this->blockFactory->create(
            Items::class,
            ['element' => $this->_rootElement->find($this->itemsBlock, Locator::SELECTOR_CSS)]
        );
    }

    /**
     * Getter for order additional block.
     *
     * @return Additional
     */
    public function getOrderAdditionalBlock()
    {
        return $this->blockFactory->create(
            Additional::class,
            ['element' => $this->_rootElement->find($this->orderAdditional, Locator::SELECTOR_CSS)]
        );
    }
}
