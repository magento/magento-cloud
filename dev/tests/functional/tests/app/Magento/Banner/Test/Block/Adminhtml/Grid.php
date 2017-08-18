<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Block\Adminhtml;

use Magento\Mtf\Client\Locator;
use Magento\Backend\Test\Block\Widget\Grid as AbstractGrid;

/**
 * Class Grid.
 * Banner grid.
 */
class Grid extends AbstractGrid
{
    /**
     * Selector for action header.
     *
     * @var string
     */
    protected $header = '.page-header';

    /**
     * Selector for footer.
     *
     * @var string
     */
    protected $footer = '.page-footer';

    /**
     * Search item via grid filter
     *
     * @param array $filter
     */
    public function search(array $filter)
    {
        $this->resetFilter();
        $this->prepareForSearch($filter);
        if (!$this->clickOnElement($this->header, $this->searchButton)) {
            $this->clickOnElement($this->footer, $this->searchButton);
        }
        $this->waitLoader();
    }

    /**
     * Press 'Reset' button
     */
    public function resetFilter()
    {
        $this->waitLoader();
        if (!$this->clickOnElement($this->header, $this->resetButton)) {
            $this->clickOnElement($this->footer, $this->resetButton);
        }
        $this->waitLoader();
    }

    /**
     * Click element on the page
     *
     * @param string $anchor
     * @param string $element
     * @param string $anchorStrategy [optional]
     * @param string $elementStrategy [optional]
     * @return bool
     */
    protected function clickOnElement(
        $anchor,
        $element,
        $anchorStrategy = Locator::SELECTOR_CSS,
        $elementStrategy = Locator::SELECTOR_CSS
    ) {
        try {
            $this->browser->find($anchor, $anchorStrategy)->hover();
            $this->_rootElement->find($element, $elementStrategy)->click();
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            return false;
        }
        return true;
    }
}
