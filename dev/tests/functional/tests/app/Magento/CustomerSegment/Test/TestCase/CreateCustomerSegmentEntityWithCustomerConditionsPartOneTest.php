<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\TestCase;

/**
 * Preconditions:
 * 1. Test customer is created.
 * 2. Test simple product is created.
 *
 * Steps:
 * 1. Login to backend as admin.
 * 2. Navigate to CUSTOMERS->Segment.
 * 3. Click 'Add Segment' button.
 * 4. Fill all fields according to dataset and click 'Save and Continue Edit' button.
 * 5. Navigate to Conditions tab.
 * 6. Add specific test condition according to dataset.
 * 7. Navigate to MARKETING->Cart Price Rule and click "+".
 * 8. Fill all required data according to dataset and save rule.
 * 9. Perform assertions
 *
 * @group Customer_Segments
 * @ZephyrId MAGETWO-25691, MAGETWO-35448, MAGETWO-35423
 */
class CreateCustomerSegmentEntityWithCustomerConditionsPartOneTest extends AbstractCreateCustomerSegmentEntityTest
{
    /* tags */
    const MVP = 'no';
    const TEST_TYPE = 'acceptance_test, extended_acceptance_test';
    /* end tags */

    // Current test case is running separately to be able parallelize full scope of Customer Segment test
}
