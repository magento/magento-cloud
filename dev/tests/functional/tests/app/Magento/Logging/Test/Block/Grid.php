<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Logging\Test\Block;

use Magento\Backend\Test\Block\Widget\Grid as GridInterface;
use Magento\Mtf\Client\Locator;

/**
 * Class Grid
 * Admin logging grid
 */
class Grid extends GridInterface
{
    /**
     * Admin action log report grid filters
     *
     * @var array
     */
    protected $filters = [
        'timeFrom' => [
            'selector' => '',
        ],
        'timeTo' => [
            'selector' => '',
        ],
        'actionGroup' => [
            'selector' => '#loggingLogGrid_filter_event',
            'input' => 'select',
        ],
        'action' => [
            'selector' => '#loggingLogGrid_filter_action',
            'input' => 'select',
        ],
        'ipAddress' => [
            'selector' => '#loggingLogGrid_filter_ip',
        ],
        'username' => [
            'selector' => '#loggingLogGrid_filter_user',
            'input' => 'select',
        ],
        'result' => [
            'selector' => '#loggingLogGrid_filter_status',
            'input' => 'select',
        ],
        'fullActionName' => [
            'selector' => '#loggingLogGrid_filter_fullaction',
        ],
        'shortDetails' => [
            'selector' => '#loggingLogGrid_filter_info',
        ],
    ];

    /**
     * Element locator to select first entity in grid
     *
     * @var string
     */
    protected $firstViewLink = "#loggingLogGrid_table tr:first-child [data-column='view'] > a";

    /**
     * Search, sort and open View link in grid
     *
     * @param array $filter
     * @throws \Exception
     */
    public function searchSortAndOpen(array $filter)
    {
        $this->search($filter);
        $this->sortGridByField('time');
        $rowItem = $this->_rootElement->find($this->rowItem, Locator::SELECTOR_CSS);
        if ($rowItem->isVisible()) {
            $rowItem->find($this->firstViewLink, Locator::SELECTOR_CSS)->click();
        } else {
            throw new \Exception("Searched item was not found by filter\n" . print_r($filter, true));
        }
    }
}
