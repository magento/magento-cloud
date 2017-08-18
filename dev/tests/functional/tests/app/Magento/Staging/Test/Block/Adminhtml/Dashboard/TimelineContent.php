<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Staging\Test\Block\Adminhtml\Dashboard;

use Magento\Mtf\Client\Locator;
use Magento\Ui\Test\Block\Adminhtml\DataGrid;

/**
 * Staging dashboard content.
 */
class TimelineContent extends DataGrid
{
    /**
     * Staging Xpath selector.
     *
     * @var string
     */
    private $timelineSelector = '//div[@class="timeline-event"]/strong[text()="%s"]';

    /**
     * Loader Xpath selector.
     *
     * @var string
     */
    protected $loader = '//div[@id="container"]//div[@data-role="spinner"]';

    /**
     * Check has staging.
     *
     * @param string $name
     * @return bool
     */
    public function hasStaging($name)
    {
        $this->fullTextSearch($name);
        $this->waitForElementNotVisible($this->loader, Locator::SELECTOR_XPATH);
        return $this->_rootElement->find(sprintf($this->timelineSelector, $name), Locator::SELECTOR_XPATH)->isVisible();
    }

    /**
     * Click on timeline event item.
     *
     * @param string $name
     * @return void
     */
    public function openTooltipByUpdateName($name)
    {
        $this->fullTextSearch($name);
        $this->waitForElementNotVisible($this->loader, Locator::SELECTOR_XPATH);
        $this->_rootElement->find(sprintf($this->timelineSelector, $name), Locator::SELECTOR_XPATH)->click();
    }
}
