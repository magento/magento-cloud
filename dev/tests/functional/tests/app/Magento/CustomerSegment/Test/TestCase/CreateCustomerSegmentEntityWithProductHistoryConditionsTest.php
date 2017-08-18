<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\TestCase;

use Magento\CustomerSegment\Test\TestStep\DeleteAllCustomerSegmentsStep;
use Magento\Catalog\Test\Fixture\CatalogProductAttribute;
use Magento\Customer\Test\Fixture\Customer;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. New attribute is created and is made available for usage in promotion rules
 *
 * Steps:
 * 1. Login to backend as admin.
 * 2. Navigate to CUSTOMERS->Segment.
 * 3. Click 'Add Segment' button.
 * 4. Fill all fields according to dataset and click 'Save and Continue Edit' button.
 * 5. Navigate to Conditions tab.
 * 6. Add specific test condition according to dataset.
 * 7. Perform assertions
 *
 * Current test case is running separately to be able parallelize full scope of Customer Segment test
 *
 * @group Customer_Segments
 * @ZephyrId MAGETWO-63461
 */
class CreateCustomerSegmentEntityWithProductHistoryConditionsTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const TEST_TYPE = 'extended_acceptance_test';
    /* end tags */

    /**
     * Customer segment index page.
     *
     * @var CustomerSegmentIndex
     */
    protected $customerSegmentIndex;

    /**
     * Page of create new customer segment.
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
    public function __inject(
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerSegmentNew $customerSegmentNew
    ) {
        $this->customerSegmentIndex = $customerSegmentIndex;
        $this->customerSegmentNew = $customerSegmentNew;
    }

    /**
     * Test customer segment creation with product history condition.
     *
     * @param CustomerSegment $customerSegment
     * @param CustomerSegment $customerSegmentConditions
     * @param CatalogProductAttribute|null $productAttribute
     */
    public function test(
        CustomerSegment $customerSegment,
        CustomerSegment $customerSegmentConditions,
        CatalogProductAttribute $productAttribute = null
    ) {
        $this->markTestSkipped('Blocked by MAGETWO-65306');
        // Preconditions
        if ($productAttribute) {
            $productAttribute->persist();
        }

        // Steps
        $this->customerSegmentIndex->open();
        $this->customerSegmentIndex->getPageActionsBlock()->addNew();
        $this->customerSegmentNew->getCustomerSegmentForm()->fill($customerSegment);
        $this->customerSegmentNew->getPageMainActions()->saveAndContinue();
        $this->customerSegmentNew->getMessagesBlock()->waitSuccessMessage();
        $this->customerSegmentNew->getCustomerSegmentForm()->openTab('conditions');
        $replace = [
            'conditions' => [
                '%color_attribute%' => $productAttribute->getAttributeCode(),
                '%color_attribute_value%' => $productAttribute->getOptions()[0]['view'],
            ],
        ];
        $this->customerSegmentNew->getCustomerSegmentForm()->fill($customerSegmentConditions, null, $replace);
        $this->customerSegmentNew->getPageMainActions()->save();
    }

    /**
     * Delete customer segments created in the test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(DeleteAllCustomerSegmentsStep::class)->run();
    }
}
