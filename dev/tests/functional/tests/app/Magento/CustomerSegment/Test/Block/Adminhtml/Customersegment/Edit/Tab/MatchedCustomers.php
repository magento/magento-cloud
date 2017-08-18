<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Block\Adminhtml\Customersegment\Edit\Tab;

use Magento\Backend\Test\Block\Widget\Tab;
use Magento\Mtf\Client\Element;

/**
 * Class MatchedCustomers
 * Matched customers tab
 */
class MatchedCustomers extends Tab
{
    /**
     * Customer grid mapping
     *
     * @var string
     */
    protected $gridPath = '#segmentGrid';

    /**
     * Get Customer Segment edit form
     *
     * @return \Magento\CustomerSegment\Test\Block\Adminhtml\Report\Customer\Segment\DetailGrid
     */
    public function getCustomersGrid()
    {
        return $this->blockFactory->create(
            \Magento\CustomerSegment\Test\Block\Adminhtml\Report\Customer\Segment\DetailGrid::class,
            ['element' => $this->_rootElement->find($this->gridPath)]
        );
    }
}
