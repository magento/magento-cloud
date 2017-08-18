<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\TestStep;

use Magento\Mtf\TestStep\TestStepInterface;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;

/**
 * Delete all Customer Segments on backend.
 */
class DeleteAllCustomerSegmentsStep implements TestStepInterface
{
    /**
     * Customer segment index page.
     *
     * @var CustomerSegmentIndex
     */
    protected $customerSegmentIndex;

    /**
     * Customer segment create page.
     *
     * @var CustomerSegmentNew
     */
    protected $customerSegmentNew;

    /**
     * @construct
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     */
    public function __construct(CustomerSegmentIndex $customerSegmentIndex, CustomerSegmentNew $customerSegmentNew)
    {
        $this->customerSegmentIndex = $customerSegmentIndex;
        $this->customerSegmentNew = $customerSegmentNew;
    }

    /**
     * Delete Customer Segments on backend.
     *
     * @return void
     */
    public function run()
    {
        $this->customerSegmentIndex->open();
        $this->customerSegmentIndex->getGrid()->resetFilter();
        while ($this->customerSegmentIndex->getGrid()->isFirstRowVisible()) {
            $this->customerSegmentIndex->getGrid()->openFirstRow();
            $this->customerSegmentNew->getPageMainActions()->delete();
            $this->customerSegmentNew->getModalBlock()->acceptAlert();
        }
    }
}
