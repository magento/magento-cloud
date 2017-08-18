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
 * @ZephyrId MAGETWO-35442, MAGETWO-35449, MAGETWO-36700
 */
class CreateCustomerSegmentEntityWithSalesConditionsTest extends Injectable
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
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Test step factory.
     *
     * @var TestStepFactory
     */
    private $stepFactory;

    /**
     * Configuration setting.
     *
     * @var string
     */
    private $configData;

    /**
     * Inject pages.
     *
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     * @param FixtureFactory $fixtureFactory
     * @param TestStepFactory $stepFactory
     * @return void
     */
    public function __inject(
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerSegmentNew $customerSegmentNew,
        FixtureFactory $fixtureFactory,
        TestStepFactory $stepFactory
    ) {
        $this->customerSegmentIndex = $customerSegmentIndex;
        $this->customerSegmentNew = $customerSegmentNew;
        $this->fixtureFactory = $fixtureFactory;
        $this->stepFactory = $stepFactory;
    }

    /**
     * Create customer segment based on sales conditions.
     *
     * @param Customer $customer
     * @param CustomerSegment $customerSegment
     * @param CustomerSegment $customerSegmentConditions
     * @param array $orders
     * @param string $configData
     * @return void
     */
    public function test(
        Customer $customer,
        CustomerSegment $customerSegment,
        CustomerSegment $customerSegmentConditions,
        array $orders,
        $configData = null
    ) {
        //Preconditions
        $this->configData = $configData;
        $this->stepFactory->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $this->configData]
        )->run();
        $customer->persist();
        $address = $customer->getDataFieldConfig('address')['source']->getAddresses()[0];
        $addressData = $address->getData();
        $addressData['street'] = [$addressData['street']];
        foreach ($orders as $order) {
            $orderFixture = $this->fixtureFactory->createByCode(
                'orderInjectable',
                [
                    'dataset' => $order['dataset'],
                    'data' => array_merge(
                        [
                            'customer_id' => ['customer' => $customer],
                            'billing_address_id' => ['value' => $addressData],
                        ],
                        isset($order['data']) ? $order['data'] : []
                    ),
                ]
            );
            $orderFixture->persist();
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
                '%country%' => $address->getCountryId(),
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
        $this->stepFactory->create(\Magento\CustomerSegment\Test\TestStep\DeleteAllCustomerSegmentsStep::class)
            ->run();
        $this->stepFactory->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $this->configData, 'rollback' => true]
        )->run();
    }
}
