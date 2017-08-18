<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Block\Sku;

use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Locator;

/**
 * Order By SKU form.
 */
abstract class AbstractSku extends Form
{
    /**
     * Add to Cart button selector
     *
     * @var string
     */
    protected $addToCart = '.action.tocart';

    /**
     * Add new row button selector
     *
     * @var string
     */
    protected $addRow = '[data-role="add"]';

    /**
     * Row selector
     *
     * @var string
     */
    protected $row = './/*[contains(@class,"fields additional") and .//*[contains(@name,"items[%d]")]]';

    /**
     * Add by SKU form selector.
     *
     * @var string
     */
    protected $addBySkuForm = '.form-addbysku';

    /**
     * Click Add to Cart button
     *
     * @return void
     */
    public function addToCart()
    {
        $this->_rootElement->find($this->addToCart)->click();
    }

    /**
     * Fill order by SKU form
     *
     * @param array $orderOptions
     * @return void
     */
    public function fillForm(array $orderOptions)
    {
        $browser = $this->browser;
        $addBySkuForm = $this->addBySkuForm;
        $browser->waitUntil(
            function () use ($browser, $addBySkuForm) {
                $element = $browser->find($addBySkuForm);
                return $element->isVisible() ? true : null;
            }
        );
        foreach ($orderOptions as $key => $value) {
            if ($key !== 0) {
                $this->_rootElement->find($this->addRow)->click();
            }
            $element = $this->_rootElement->find(sprintf($this->row, $key), Locator::SELECTOR_XPATH);
            $mapping = $this->dataMapping($value);
            $this->_fill($mapping, $element);
        }
    }
}
