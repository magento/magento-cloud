<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Staging\Test\Block\Adminhtml;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Staging campaign form page actions.
 */
class PageActions extends Block
{
    /**
     * Save button css selector.
     *
     * @var string
     */
    private $saveButton = 'button[data-ui-id="save-button"]';

    /**
     * Preview button css selector.
     *
     * @var string
     */
    private $previewButton = 'button[data-ui-id="preview-button"]';

    /**
     * Remove button css selector.
     *
     * @var string
     */
    private $removeButton = 'button[data-ui-id="remove-button"]';

    /**
     * Loading mask css selector.
     *
     * @var string
     */
    protected $loader = '.loading-mask';

    /**
     * Scope CSS selector
     *
     * @var string
     */
    protected $scopeSelector = '.store-switcher .actions.dropdown';

    /**
     * Click save button.
     *
     * @return void
     */
    public function save()
    {
        $this->_rootElement->find($this->saveButton)->click();
        $this->waitForElementNotVisible($this->loader);
    }

    /**
     * Click preview button.
     *
     * @return void
     */
    public function preview()
    {
        $this->_rootElement->find($this->previewButton)->click();
        $this->waitForElementNotVisible($this->loader);
    }

    /**
     * Click remove button.
     *
     * @return void
     */
    public function remove()
    {
        $this->_rootElement->find($this->removeButton)->click();
        $this->waitForElementNotVisible($this->loader);
    }

    /**
     * Select Store View.
     *
     * @param string $websiteScope
     * @return void
     */
    public function selectStoreView($websiteScope)
    {
        $this->_rootElement->find($this->scopeSelector, Locator::SELECTOR_CSS, 'liselectstore')
            ->setValue($websiteScope);
    }
}
