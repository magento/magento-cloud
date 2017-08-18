<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Block\Adminhtml;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;
use Magento\Staging\Test\Fixture\Update;

/**
 * "Scheduled Product Changes" block on product page.
 */
class ProductSchedule extends Block
{
    /**
     * "Schedule New Update" button CSS selector.
     *
     * @var string
     */
    private $scheduleUpdateButton = '#staging_update_new';

    /**
     * Xpath locator for edit staging campaign link.
     *
     * @var string
     */
    private $viewLink = '//tr[td//*[contains(text(),"%s")]]//a[not(@href)]';

    /**
     * Xpath locator for preview campaign link.
     *
     * @var string
     */
    private $previewLink = '//tr[td//*[contains(text(),"%s")]]//a[contains(@href, "update/preview")]';

    /**
     * Xpath locator for start schedule date.
     *
     * @var string
     */
    private $startDate = '//tr[td//*[contains(text(),"%s")] and @class="schedule-start"]//time';

    /**
     * Xpath locator for end schedule date.
     *
     * @var string
     */
    private $endDate = '//tr[td//*[contains(text(),"%s")]]/following-sibling::tr//time';

    /**
     * Xpath locator for campaign name.
     *
     * @var string
     */
    private $campaignName = '//div[@class="data-grid-cell-content" and text()="%s"]';

    /**
     * Xpath locator for data grid loader.
     *
     * @var string
     */
    private $updateBlockLoader = '//div[contains(@data-component, "catalogstaging_upcoming_form")]';

    /**
     * Xpath locator for product edit page spinner.
     *
     * @var string
     */
    private $productPageLoader = '//div[contains(@data-component, "product_form")]';

    /**
     * Xpath locator for data grid content loader.
     *
     * @var string
     */
    private $updateBlockContentLoader = '//div[contains(@data-component, "catalogstaging_upcoming_grid.columns")]';

    /**
     * Click "Schedule New Update" button.
     *
     * @return void
     */
    public function clickScheduleNewUpdate()
    {
        $this->waitForElementNotVisible($this->updateBlockLoader);
        $this->_rootElement->find($this->scheduleUpdateButton)->click();
        $this->waitForElementNotVisible($this->updateBlockLoader);
    }

    /**
     * Click "View/Edit" link.
     *
     * @param string $updateName
     * @return void
     */
    public function editUpdate($updateName)
    {
        $this->_rootElement->find(sprintf($this->viewLink, $updateName), Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Click "Preview" link.
     *
     * @param string $updateName
     * @return void
     */
    public function previewUpdate($updateName)
    {
        $this->_rootElement->find(sprintf($this->previewLink, $updateName), Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Get product update campaign start date.
     *
     * @param Update $stage
     * @return array
     */
    public function getStartDate(Update $stage)
    {
        $startDateElements = $this->_rootElement
            ->getElements(sprintf($this->startDate, $stage->getName()), Locator::SELECTOR_XPATH);
        $startDate = [];
        foreach ($startDateElements as $element) {
            $startDate[] = $element->getText();
        }
        return $startDate;
    }

    /**
     * Get product update campaign end date.
     *
     * @param Update $stage
     * @return array
     */
    public function getEndDate(Update $stage)
    {
        $endDateElements = $this->_rootElement
            ->getElements(sprintf($this->endDate, $stage->getName()), Locator::SELECTOR_XPATH);
        $endDate = [];
        foreach ($endDateElements as $element) {
            $endDate[] = $element->getText();
        }
        return $endDate;
    }

    /**
     * Verifies if campaign with specified name exists in product Scheduled Changes block.
     *
     * @param string $campaignName
     * @return bool
     */
    public function updateCampaignExists($campaignName)
    {
        $this->waitForElementNotVisible($this->productPageLoader, Locator::SELECTOR_XPATH);
        $this->waitForElementNotVisible($this->updateBlockLoader, Locator::SELECTOR_XPATH);
        $this->waitForElementNotVisible($this->updateBlockContentLoader, Locator::SELECTOR_XPATH);
        return $this->_rootElement
            ->find(sprintf($this->campaignName, $campaignName), Locator::SELECTOR_XPATH)
            ->isVisible();
    }
}
