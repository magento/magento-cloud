<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

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
 * @group Customer_Segments_(CS)
 * @ZephyrId MAGETWO-25691
 */
class CreateCustomerSegmentEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'CS';
    const TEST_TYPE = 'acceptance_test, extended_acceptance_test';
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
     * Update customer. Create customer segment.
     *
     * @param FixtureFactory $fixtureFactory
     * @param Customer $customer
     * @param CustomerSegment $customerSegment
     * @param CustomerSegment $customerSegmentConditions
     * @param bool $isAddCustomerBalance
     * @param CustomerSegment|null $customerSegment2
     */
    public function test(
        FixtureFactory $fixtureFactory,
        Customer $customer,
        CustomerSegment $customerSegment,
        CustomerSegment $customerSegmentConditions,
        $isAddCustomerBalance = false,
        CustomerSegment $customerSegment2 = null
    ) {
        $customer->persist();
        if ($isAddCustomerBalance) {
            $customerBalance = $fixtureFactory->createByCode(
                'customerBalance',
                ['dataset' => 'customerBalance_100', 'data' => ['customer_id' => ['customer' => $customer]]]
            );
            $customerBalance->persist();
        }
        if ($customerSegment2) {
            $customerSegment2->persist();
        }
        //Prepare data
        $address = $customer->getDataFieldConfig('address')['source']->getAddresses()[0];
        $replace = [
            'conditions' => [
                '%email%' => $customer->getEmail(),
                '%company%' => $address->getCompany(),
                '%address%' => $address->getStreet(),
                '%telephone%' => $address->getTelephone(),
                '%postcode%' => $address->getPostcode(),
                '%province%' => $address->getRegionId(),
                '%city%' => $address->getCity(),
            ],
        ];

        //Steps
        $this->customerSegmentIndex->open();
        $this->customerSegmentIndex->getPageActionsBlock()->addNew();
        $this->customerSegmentNew->getCustomerSegmentForm()->fill($customerSegment);
        $this->customerSegmentNew->getPageMainActions()->saveAndContinue();
        $this->customerSegmentNew->getMessagesBlock()->waitSuccessMessage();
        $this->customerSegmentNew->getCustomerSegmentForm()->openTab('conditions');
        $this->customerSegmentNew->getCustomerSegmentForm()->fill($customerSegmentConditions, null, $replace);
        $this->customerSegmentNew->getPageMainActions()->save();
    }

    /**
     * Deleting cart price rules and customer segments.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create('Magento\SalesRule\Test\TestStep\DeleteAllSalesRuleStep')->run();
        $this->objectManager->create('Magento\CustomerSegment\Test\TestStep\DeleteAllCustomerSegmentsStep')->run();
    }
}
