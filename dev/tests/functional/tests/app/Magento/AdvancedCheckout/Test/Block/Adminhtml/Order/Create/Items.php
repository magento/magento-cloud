<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Block\Adminhtml\Order\Create;

use Magento\Mtf\Client\Locator;

/**
 * Adminhtml sales order create items block.
 */
class Items extends \Magento\Sales\Test\Block\Adminhtml\Order\Create\Items
{
    /**
     * 'Add to Order' button.
     *
     * @var string
     */
    protected $addToOrder = "//button[@onclick='addBySku.submitSkuForm()']";

    /**
     * 'Add Products to Order' button.
     *
     * @var string
     */
    protected $addConfigured = "//button[@onclick='addBySku.submitConfigured()']";

    /**
     * 'Add Products By SKU' button.
     *
     * @var string
     */
    protected $addProductsBySku = "//*[@id='order-items']//button[1]";

    /**
     * Order error message.
     *
     * @var string
     */
    protected $orderErrorMessage = "//span[@class='title']/span";

    /**
     * Click 'Add Products By SKU' button.
     *
     * @return void
     */
    public function clickAddProductsBySku()
    {
        $this->_rootElement->find($this->addProductsBySku, Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Click 'Add to Order' button.
     *
     * @return void
     */
    public function clickAddToOrder()
    {
        $this->_rootElement->find($this->addToOrder, Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Click 'Add Products to Order' button.
     *
     * @return void
     */
    public function clickConfiguredToOrder()
    {
        $this->_rootElement->find($this->addConfigured, Locator::SELECTOR_XPATH)->click();
    }
}
