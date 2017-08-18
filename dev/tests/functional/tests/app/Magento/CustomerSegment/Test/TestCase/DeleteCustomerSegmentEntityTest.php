<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\TestCase;

use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create a customer segment.
 *
 * Steps:
 * 1. Login to backend as admin.
 * 2. Use the main menu "CUSTOMERS"->"Segments".
 * 3. Search and open created segment.
 * 4. Click the "Delete" link.
 * 5. Click the "OK" button.
 * 6. Perform the assertions according to the Data Set.
 *
 * @group Customer_Segments
 * @ZephyrId MAGETWO-26791
 */
class DeleteCustomerSegmentEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

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
     * Inject pages.
     *
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     * @return void
     */
    public function __inject(CustomerSegmentIndex $customerSegmentIndex, CustomerSegmentNew $customerSegmentNew)
    {
        $this->customerSegmentIndex = $customerSegmentIndex;
        $this->customerSegmentNew = $customerSegmentNew;
    }

    /**
     * Delete Customer Segment.
     *
     * @param CustomerSegment $customerSegment
     * @return void
     */
    public function test(CustomerSegment $customerSegment)
    {
        // Precondition
        $customerSegment->persist();

        // Steps
        $this->customerSegmentIndex->open();
        $this->customerSegmentIndex->getGrid()->searchAndOpen(
            [
                'grid_segment_name' => $customerSegment->getName(),
            ]
        );
        $this->customerSegmentNew->getPageMainActions()->delete();
        $this->customerSegmentNew->getModalBlock()->acceptAlert();
    }
}
