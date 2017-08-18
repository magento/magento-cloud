<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndex;
use Magento\CustomerBalance\Test\Fixture\CustomerBalance;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\CustomerSegment\Test\Fixture\CustomerSegmentSalesRule as SalesRule;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Delete all existed customers.
 * 2. Test customer segment is created according to specified predefined dataset.
 * 3. Test customer is created according to specified predefined dataset.
 * 4. Test simple product is created.
 * 5. CartPriceRule which contains in conditions created customer segment is created.
 *
 * Steps:
 * 1. Login to backend as admin.
 * 2. Use the main menu "CUSTOMERS"->"Segments".
 * 3. Search and open created segment.
 * 4. Fill all fields according to dataset and click 'Save and Continue Edit' button.
 * 5. Navigate to Conditions tab.
 * 6. Click the "Remove" button for a original conditions.
 * 7. Perform assertions.
 *
 * @group Customer_Segments
 * @ZephyrId MAGETWO-26420
 */
class UpdateCustomerSegmentEntityTest extends Injectable
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
     * Page of create new customer segment.
     *
     * @var CustomerSegmentNew
     */
    protected $customerSegmentNew;

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Inject pages.
     *
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     * @param CustomerIndex $customerIndexPage
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __inject(
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerSegmentNew $customerSegmentNew,
        CustomerIndex $customerIndexPage,
        FixtureFactory $fixtureFactory
    ) {
        $this->customerSegmentIndex = $customerSegmentIndex;
        $this->customerSegmentNew = $customerSegmentNew;
        $this->fixtureFactory = $fixtureFactory;
        $customerIndexPage->open();
        $customerIndexPage->getCustomerGridBlock()->massaction([], 'Delete', true, 'Select All');
    }

    /**
     * Update customer. Create customer segment.
     *
     * @param Customer $customer
     * @param CustomerSegment $customerSegment
     * @param CustomerSegment $customerSegmentOriginal
     * @param bool $isAddCustomerBalance
     * @return array
     */
    public function test(
        Customer $customer,
        CustomerSegment $customerSegment,
        CustomerSegment $customerSegmentOriginal,
        $isAddCustomerBalance = false
    ) {
        //Preconditions
        $customer->persist();
        if ($isAddCustomerBalance) {
            $customerBalance = $this->fixtureFactory->createByCode(
                'customerBalance',
                ['dataset' => 'customerBalance_100', 'data' => ['customer_id' => ['customer' => $customer]]]
            );
            $customerBalance->persist();
        }
        $customerSegmentOriginal->persist();

        //Steps
        $this->customerSegmentIndex->open();
        $this->customerSegmentIndex->getGrid()->searchAndOpen(
            [
                'grid_segment_name' => $customerSegmentOriginal->getName(),
            ]
        );
        $this->customerSegmentNew->getCustomerSegmentForm()->fill($customerSegment);
        $this->customerSegmentNew->getPageMainActions()->save();

        return ['customerSegment' => $this->mergeFixture($customerSegment, $customerSegmentOriginal)];
    }

    /**
     * Merge Customer Segment fixtures.
     *
     * @param CustomerSegment $segment
     * @param CustomerSegment $segmentOriginal
     * @return CustomerSegment
     */
    protected function mergeFixture(CustomerSegment $segment, CustomerSegment $segmentOriginal)
    {
        $data = array_merge($segmentOriginal->getData(), $segment->getData());
        return $this->fixtureFactory->createByCode('customerSegment', ['data' => $data]);
    }

    /**
     * Deleting cart price rules and customer segments.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(\Magento\SalesRule\Test\TestStep\DeleteAllSalesRuleStep::class)->run();
        $this->objectManager->create(\Magento\CustomerSegment\Test\TestStep\DeleteAllCustomerSegmentsStep::class)
            ->run();
    }
}
