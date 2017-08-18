<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Block\Adminhtml\Customersegment\Grid;

use Magento\Backend\Test\Block\Widget\Grid;
use Magento\Mtf\Client\Locator;

/**
 * Class Chooser
 * Backend customer segment grid
 */
class Chooser extends Grid
{
    /**
     * An element locator which allows to select entities in grid
     *
     * @var string
     */
    protected $selectItem = 'tbody tr .checkbox';

    /**
     * 'Select All' link
     *
     * @var string
     */
    protected $selectAll = '//table[@id="customersegment_grid_chooser_customersegmentGrid_table"]/thead/tr/th[1]/input';

    /**
     * Chooser grid mapping
     *
     * @var array
     */
    protected $filters = [
        'name' => [
            'selector' => 'input[name="grid_segment_name"]',
        ],
    ];

    /**
     * Search for items and select all
     *
     * @param array $filter
     * @throws \Exception
     * @return void
     */
    public function searchAndSelect(array $filter)
    {
        $this->search($filter);
        $selectAll = $this->_rootElement->find($this->selectAll, Locator::SELECTOR_XPATH, 'checkbox');
        if ($selectAll->isVisible()) {
            $selectAll->click();
        } else {
            throw new \Exception("Searched item was not found by filter\n" . print_r($filter, true));
        }
    }
}
