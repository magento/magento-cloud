<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\TestCase\Injectable;
use Magento\Mtf\TestStep\TestStepFactory;

/**
 * Preconditions:
 * 1. Test customer is created.
 * 2. Test simple product is created.
 *
 * Steps:
 * 1. Login customer on frontend.
 * 2. Add created products to customer's wishlist.
 * 3. Login to backend as admin.
 * 4. Navigate to CUSTOMERS->Segment.
 * 5. Click 'Add Segment' button.
 * 6. Fill all fields according to dataset and click 'Save and Continue Edit' button.
 * 7. Navigate to Conditions tab.
 * 8. Add specific test condition according to dataset.
 * 9. Perform assertions.
 *
 * @group Customer_Segments
 * @ZephyrId MAGETWO-35446, MAGETWO-35444
 */
class CreateCustomerSegmentEntityWithProductsConditionsTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Customer segment index page.
     *
     * @var CustomerSegmentIndex
     */
    private $customerSegmentIndex;

    /**
     * Page of create new customer segment.
     *
     * @var CustomerSegmentNew
     */
    private $customerSegmentNew;

    /**
     * Factory for Test Steps.
     *
     * @var TestStepFactory
     */
    private $testStepFactory;

    /**
     * Inject pages.
     *
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     * @param TestStepFactory $testStepFactory
     * @return void
     */
    public function __inject(
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerSegmentNew $customerSegmentNew,
        TestStepFactory $testStepFactory
    ) {
        $this->customerSegmentIndex = $customerSegmentIndex;
        $this->customerSegmentNew = $customerSegmentNew;
        $this->testStepFactory = $testStepFactory;
    }

    /**
     * Products creation. Login customer on frontend. Adding products to wishlist.
     *
     * @param Customer $customer
     * @param CatalogProductSimple $product
     * @param CustomerSegment $customerSegment
     * @param CustomerSegment $customerSegmentConditions
     * @return void
     */
    public function test(
        Customer $customer,
        CatalogProductSimple $product,
        CustomerSegment $customerSegment,
        CustomerSegment $customerSegmentConditions
    ) {
        $product->persist();
        $customer->persist();
        $this->testStepFactory->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $this->testStepFactory->create(
            \Magento\Wishlist\Test\TestStep\AddProductsToWishlistStep::class,
            ['products' => [$product]]
        )->run();

        //Prepare data
        $replace = [
            'conditions' => [
                '%category%' => $product->getDataFieldConfig('category_ids')['source']->getIds()[0],
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
        $this->testStepFactory->create(\Magento\CustomerSegment\Test\TestStep\DeleteAllCustomerSegmentsStep::class)
            ->run();
    }
}
