<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Staging\Test\Block\Adminhtml;

use Magento\Ui\Test\Block\Adminhtml\DataGrid;
use Magento\Mtf\Client\Locator;

/**
 * Grid with update campaigns.
 */
class StagingGrid extends DataGrid
{
    /**
     * Loading mask Xpath selector.
     *
     * @var string
     */
    protected $loader = '//div[@data-index="staging_select"]//div[@data-role="spinner"]';

    /**
     * "Assign to Existing Campaign" radio Xpath selector.
     *
     * @var string
     */
    protected $assignCampaignRadio = '//input[@data-index="staging_select_mode"]';

    /**
     * Search input css selector.
     *
     * @var string
     */
    protected $searchInput = '#fulltext';

    /**
     * Search submit button css selector.
     *
     * @var string
     */
    protected $submitButton = 'button.action-submit';

    /**
     * Css locator for data grid loader.
     *
     * @var string
     */
    protected $dataGridLoadingMask = '.admin__data-grid-loading-mask';

    /**
     * "Select" button Xpath selector.
     *
     * @var string
     */
    protected $campaignButtonSelect = '//tr[td/*[contains(text(), "%s")]]//button';

    /**
     * Data row Xpath selector.
     *
     * @var string
     */
    protected $dataRow = '//tr[contains(@class, "_disabled")]//div[text()="%s"]';

    /**
     * Select existing campaign.
     *
     * @param string $campaignName
     * @return void
     */
    public function assignToExistingCampaign($campaignName)
    {
        $this->clickAssignToExistingCampaign();
        $this->fullTextSearch($campaignName);
        $this->waitForElementNotVisible($this->loader, Locator::SELECTOR_XPATH);
        $this->_rootElement->find(
            sprintf($this->campaignButtonSelect, $campaignName),
            Locator::SELECTOR_XPATH
        )->click();
    }

    /**
     * Select Assign to Existing Campaign option.
     *
     * @return void
     */
    public function clickAssignToExistingCampaign()
    {
        $this->waitForElementNotVisible($this->loader, Locator::SELECTOR_XPATH);
        $this->_rootElement->find($this->assignCampaignRadio, Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Check if campaign is disabled.
     *
     * @param string $campaignName
     * @return bool
     */
    public function campaignIsBlocked($campaignName)
    {
        $this->fullTextSearch($campaignName);
        $this->waitForElementNotVisible($this->loader, Locator::SELECTOR_XPATH);
        return $this->_rootElement->find(sprintf($this->dataRow, $campaignName), Locator::SELECTOR_XPATH)->isVisible();
    }
}
