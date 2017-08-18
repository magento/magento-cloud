<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Staging\Test\Block\Adminhtml\Preview;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Block with preview options in update campaign preview.
 */
class PreviewOptions extends Block
{
    /**
     * Calendar button Xpath selector.
     *
     * @var string
     */
    private $calendarButton = '//div[contains(@class, "staging-preview-item-calendar")]';

    /**
     * Scope button Xpath selector.
     *
     * @var string
     */
    private $scopeButton = '//div[contains(@class, "staging-preview-item-customer")]';

    /**
     * Date input field CSS selector.
     *
     * @var string
     */
    private $dateInput = '#staging_date';

    /**
     * "Preview" button in calendar Xpath selector.
     *
     * @var string
     */
    private $previewButtonInCalendar = '(//button[@data-role="update-button"])[1]';

    /**
     * "Preview" button in scope Xpath selector.
     *
     * @var string
     */
    private $previewButtonInScope = '(//button[@data-role="update-button"])[2]';

    /**
     * Store view CSS selector.
     *
     * @var string
     */
    private $storeViewSelect = '.staging-preview-item-segment .admin__control-select';

    /**
     * Mage init xpath selector.
     *
     * @var string
     */
    private $bodyMageInit = '//body[@data-mage-init]';

    /**
     * Switch date.
     *
     * @param string $date
     * @return void
     */
    public function switchDate($date)
    {
        $date = date('M d, Y g:i A', strtotime($date));
        $this->waitForIframeToLoad();
        $this->_rootElement->find($this->calendarButton, Locator::SELECTOR_XPATH)->click();
        $this->_rootElement->find($this->dateInput)->setValue($date);
    }

    /**
     * Click "Preview" button in Calendar section.
     *
     * @return void
     */
    public function clickPreviewInCalendar()
    {
        $this->_rootElement->find($this->previewButtonInCalendar, Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Click "Preview" button in Scope section.
     *
     * @return void
     */
    public function clickPreviewInScope()
    {
        $this->_rootElement->find($this->previewButtonInScope, Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Switch scope.
     *
     * @param string $websiteName
     * @return void
     * @throws \Exception
     */
    public function switchScope($websiteName)
    {
        $this->waitForIframeToLoad();
        $this->_rootElement->find($this->scopeButton, Locator::SELECTOR_XPATH)->click();
        $this->_rootElement->find($this->storeViewSelect)->click();

        $pieces = explode('/', $websiteName);

        if (1 == count($pieces)) {
            $optionLocator = './/option[contains(text(),"' . $pieces[0] . '")]';
        } else {
            $optionLocator = './/optgroup[contains(@label,"'
                . $pieces[0] . '")]/following-sibling::optgroup[contains(@label,"'
                . $pieces[1] . '")]/option[contains(text(), "'
                . $pieces[2] . '")]';
        }

        $option = $this->_rootElement->find($this->storeViewSelect, Locator::SELECTOR_CSS)
            ->find($optionLocator, Locator::SELECTOR_XPATH);
        if (!$option->isVisible()) {
            throw new \Exception('[' . implode('/', $pieces) . '] option is not visible in store switcher.');
        }
        $option->doubleClick();
    }

    /**
     * Wait for iframe to load.
     *
     * @return void
     */
    private function waitForIframeToLoad()
    {
        $this->waitForElementNotVisible($this->bodyMageInit, Locator::SELECTOR_XPATH);
        $this->browser->selectWindow();
    }
}
