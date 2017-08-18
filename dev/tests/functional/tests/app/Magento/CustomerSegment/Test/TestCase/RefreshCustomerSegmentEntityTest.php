<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndex;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Delete all existed customers.
 * 2. Test segments are created according to specified predefined dataset.
 * 3. Test customers are created on frontend according to specified predefined dataset.
 *
 * Steps:
 * 1. Login to backend as admin.
 * 2. Use the main menu "CUSTOMERS" -> "Segments"
 * 3. Search and open created segment.
 * 4. Click the "Refresh Segment Data" button.
 * 5. Perform assertions.
 *
 * @group Customer_Segments
 * @ZephyrId MAGETWO-26786
 */
class RefreshCustomerSegmentEntityTest extends Injectable
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
     * New Customer Segment page.
     *
     * @var CustomerSegmentNew
     */
    protected $customerSegmentNew;

    /**
     * Inject pages and delete all customers.
     *
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     * @param CustomerIndex $customerIndexPage
     * @return void
     */
    public function __inject(
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerSegmentNew $customerSegmentNew,
        CustomerIndex $customerIndexPage
    ) {
        $this->customerSegmentIndex = $customerSegmentIndex;
        $this->customerSegmentNew = $customerSegmentNew;
        $customerIndexPage->open();
        $customerIndexPage->getCustomerGridBlock()->massaction([], 'Delete', true, 'Select All');
    }

    /**
     * Refresh Customer Segment Entity.
     *
     * @param Customer $customer
     * @param CustomerSegment $customerSegment
     * @return void
     */
    public function test(
        Customer $customer,
        CustomerSegment $customerSegment
    ) {
        //Preconditions
        $customer->persist();
        $customerSegment->persist();

        //Steps
        $this->customerSegmentIndex->open();
        $this->customerSegmentIndex->getGrid()->searchAndOpen(
            [
                'grid_segment_name' => $customerSegment->getName(),
            ]
        );
        $this->customerSegmentNew->getPageMainActions()->refreshSegmentData();
    }

    /**
     * Deleting customer segments.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(\Magento\CustomerSegment\Test\TestStep\DeleteAllCustomerSegmentsStep::class)
            ->run();
    }
}
