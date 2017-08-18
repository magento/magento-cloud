<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Block\Adminhtml\Order\Create;

use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Locator;

/**
 * Adminhtml sales order additional block.
 */
class Additional extends Form
{
    /**
     * 'Add another' button.
     *
     * @var string
     */
    protected $addAnother = "//*[@id='sku_container']//tr[1]//button";

    /**
     * Row with sku and qty fields selector.
     *
     * @var string
     */
    protected $row = "//*[@id='sku_container']//tr[%d]";

    /**
     * Fill order by SKU form.
     *
     * @param array $orderOptions
     * @return void
     */
    public function fillForm(array $orderOptions)
    {
        foreach ($orderOptions as $key => $value) {
            if ($key !== 0) {
                $this->_rootElement->find($this->addAnother, Locator::SELECTOR_XPATH)->click();
            }
            $element = $this->_rootElement->find(sprintf($this->row, $key + 1), Locator::SELECTOR_XPATH);
            $mapping = $this->dataMapping($value);
            $this->_fill($mapping, $element);
        }
    }
}
