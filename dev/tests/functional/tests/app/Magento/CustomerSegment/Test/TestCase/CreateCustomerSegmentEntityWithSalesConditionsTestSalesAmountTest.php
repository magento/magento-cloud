<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepFactory;
use Magento\Mtf\TestCase\Injectable;
use Magento\CustomerSegment\Test\TestCase\CreateCustomerSegmentEntityWithSalesConditionsTest as CreateCustomerSegment;

/**
 * Preconditions:
 * 1. Customer with default address is created.
 * 2. An order is placed by the test customer using default address.
 *
 * Steps:
 * 1. Login to backend as admin.
 * 2. Navigate to Customers > Segments.
 * 3. Click 'Add Segment' button.
 * 4. Fill all fields according to dataset and click 'Save and Continue Edit' button.
 * 5. Navigate to Conditions tab.
 * 6. Add specific test condition according to dataset.
 * 7. Perform assertions.
 *
 * @group Customer_Segments
 * @ZephyrId MAGETWO-36700
 */
class CreateCustomerSegmentEntityWithSalesConditionsTestSalesAmountTest extends CreateCustomerSegment
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    // This blank class is created only to run long variation(s) as a separate test in parallel environment
}
