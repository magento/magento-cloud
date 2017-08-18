<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Block\Adminhtml\Report\Customer\Segment;

use Magento\Backend\Test\Block\Widget\Grid as WidgetGrid;
use Magento\Mtf\Client\Locator;

/**
 * Class DetailGrid
 * Customer segment report grid
 */
class DetailGrid extends WidgetGrid
{
    /**
     * XPath for segment grid row
     */
    const GRID_XPATH_ROW = '//table[@id="segmentGrid_table"]/tbody/tr';

    /**
     * The Xpath column for the Name
     */
    const GRID_NAME_COL = '/td[2]';

    /**
     * The Xpath column for the Email
     */
    const GRID_EMAIL_COL = '/td[3]';

    /**
     * The Xpath column for the Group
     */
    const GRID_GROUP_COL = '/td[4]';

    /**
     * The total records path
     */
    const TOTAL_RECORDS = '#segmentGrid-total-count';

    /**
     * Filters array mapping
     *
     * @var array
     */
    protected $filters = [
        'entity_id_from' => [
            'selector' => 'input[name="grid_entity_id[from]"]',
        ],
        'entity_id_to' => [
            'selector' => 'input[name="grid_entity_id[to]"]',
        ],
        'grid_name' => [
            'selector' => 'input[name="grid_name"]',
        ],
        'grid_email' => [
            'selector' => 'input[name="grid_email"]',
        ],
        'grid_group' => [
            'selector' => 'select[name="grid_group"]',
            'input' => 'select',
        ],
        'grid_telephone' => [
            'selector' => 'input[name="grid_telephone"]',
        ],
        'grid_billing_postcode' => [
            'selector' => 'input[name="grid_billing_postcode"]',
        ],
        'grid_billing_country_id' => [
            'selector' => 'select[name="grid_billing_country_id"]',
            'input' => 'select',
        ],
        'grid_billing_region' => [
            'selector' => 'input[name="grid_billing_region"]',
        ],
        'grid_customer_since_from' => [
            'selector' => 'input[name="grid_customer_since[from]"]',
        ],
        'grid_customer_since_to' => [
            'selector' => 'input[name="grid_customer_since[to]"]',
        ],
    ];

    /**
     * Get Name text from matched customer grid
     *
     * @return string
     */
    public function getGridName()
    {
        return $this->_rootElement->find(self::GRID_XPATH_ROW . self::GRID_NAME_COL, Locator::SELECTOR_XPATH)
            ->getText();
    }

    /**
     * Get Email text from matched customer grid
     *
     * @return string
     */
    public function getGridEmail()
    {
        return $this->_rootElement->find(self::GRID_XPATH_ROW . self::GRID_EMAIL_COL, Locator::SELECTOR_XPATH)
            ->getText();
    }

    /**
     * Get Group text from matched customer grid
     *
     * @return string
     */
    public function getGridGroup()
    {
        return $this->_rootElement->find(self::GRID_XPATH_ROW . self::GRID_GROUP_COL, Locator::SELECTOR_XPATH)
            ->getText();
    }

    /**
     * Get total records in grid
     *
     * @return int
     */
    public function getTotalRecords()
    {
        return $this->_rootElement->find(self::TOTAL_RECORDS, Locator::SELECTOR_CSS)->getText();
    }
}
