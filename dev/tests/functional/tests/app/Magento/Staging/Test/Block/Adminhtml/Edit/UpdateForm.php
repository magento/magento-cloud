<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Staging\Test\Block\Adminhtml\Edit;

use Magento\Ui\Test\Block\Adminhtml\FormSections;

/**
 * Update campaign form on campaign edit page.
 */
class UpdateForm extends FormSections
{
    /**
     * Staging edit page loader Xpath locator.
     *
     * @var string
     */
    private $stagingEditPageLoader = '//div[contains(@data-component, "staging_update_edit")]';

    /**
     * Css selector for products grid loader.
     *
     * @var string
     */
    private $gridSelector = '.admin__data-grid-outer-wrap';

    /**
     * Data grid loader selector.
     *
     * @var string
     */
    private $loader = '[data-role="spinner"]';

    /**
     * Expand section by its name.
     *
     * @param string $sectionName
     * @return void
     */
    public function openSection($sectionName)
    {
        $this->waitForElementNotVisible($this->stagingEditPageLoader);
        parent::openSection($sectionName);
        $this->waitForGridToLoad($sectionName);
    }

    /**
     * Wait for products grid to load.
     *
     * @param string $sectionName
     * @return void
     */
    private function waitForGridToLoad($sectionName)
    {
        $context = $this->getContainerElement($sectionName);
        $context->waitUntil(
            function () use ($context) {
                return $context->find($this->gridSelector)->isVisible() ? true : null;
            }
        );
        $context->waitUntil(
            function () use ($context) {
                return $context->find($this->loader)->isVisible() ? null : true;
            }
        );
    }
}
