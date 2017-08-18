<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reminder\Test\Block\Adminhtml\Reminder\Edit\General;

use Magento\Mtf\ObjectManager;
use Magento\Mtf\Client\Locator;
use Magento\SalesRule\Test\Block\Adminhtml\Promo\Grid as SalesRuleGrid;

/**
 * Typified element class for sales rule
 */
class SalesruleId extends \Magento\Mtf\Client\Element\SimpleElement
{
    /**
     * Locator for "Select Rule" button.
     *
     * @var string
     */
    protected $selectRuleButton = './/ancestor::body//div[contains(@class,"field-choosersalesrule_id")]//button';

    // @codingStandardsIgnoreStart
    /**
     * Locator for popup "Select Rule" grid.
     *
     * @var string
     */
    protected $popupSelectRuleGrid = './/ancestor::body//div[contains(@class,"modals-wrapper") and .//div[contains(@id,"salesrule_id")]]';
    // @codingStandardsIgnoreEnd

    /**
     * Locator for loader.
     *
     * @var string
     */
    protected $loader = './/ancestor::body/div[@id="loading-mask"]';

    /**
     * Set value.
     *
     * @param string $value
     * @return void
     */
    public function setValue($value)
    {
        $this->selectRule();
        $this->getSalesRuleGrid()->searchAndSelect(['name' => $value]);
        $this->waitLoader();
    }

    /**
     * Return value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->getText();
    }

    /**
     * Click "Select Rule" button.
     *
     * @return void
     */
    protected function selectRule()
    {
        $this->find($this->selectRuleButton, Locator::SELECTOR_XPATH)->click();
        $this->waitLoader();
    }

    /**
     * Get sales rule grid.
     *
     * @return SalesRuleGrid
     */
    protected function getSalesRuleGrid()
    {
        return ObjectManager::getInstance()->create(
            \Magento\SalesRule\Test\Block\Adminhtml\Promo\Grid::class,
            ['element' => $this->find($this->popupSelectRuleGrid, Locator::SELECTOR_XPATH)]
        );
    }

    /**
     * Wait until loader not visible.
     *
     * @return void
     */
    protected function waitLoader()
    {
        $browser = $this;
        $selector = $this->loader;

        $browser->waitUntil(
            function () use ($browser, $selector) {
                $element = $browser->find($selector, Locator::SELECTOR_XPATH);
                return $element->isVisible() == false ? true : null;
            }
        );
    }
}
