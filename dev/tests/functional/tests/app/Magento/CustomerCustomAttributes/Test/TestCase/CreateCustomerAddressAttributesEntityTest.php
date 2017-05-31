<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\TestCase;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerAddressAttribute;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAddressAttributeIndex;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAddressAttributeNew;
use Magento\CustomerCustomAttributes\Test\TestStep\DeleteCustomerAddressAttributeStep;
use Magento\Mtf\TestCase\Injectable;
use Magento\Mtf\TestStep\TestStepFactory;

/**
 * Steps:
 * 1. Open Backend
 * 2. Go to Stores -> Attribute -> Customer Address
 * 3. Click Add new attribute
 * 4. Fill data according to dataset
 * 5. Save
 * 6. Perform all assertions
 *
 * @group Customer_Attributes_(CS)
 * @ZephyrId MAGETWO-67456
 */
class CreateCustomerAddressAttributesEntityTest extends Injectable
{
    /**
     * Custom address attribute.
     *
     * @var CustomerAddressAttribute
     */
    protected $addressCustomAttribute;

    /**
     * Backend page with the list of customer address attributes.
     *
     * @var CustomerAddressAttributeIndex
     */
    protected $customerAttributeIndex;

    /**
     * Backend page with new customer address attribute form.
     *
     * @var CustomerAddressAttributeNew
     */
    protected $customerAttributeNew;

    /**
     * Factory responsible for test steps creation.
     *
     * @var TestStepFactory
     */
    protected $testStepFactory;

    /**
     * Injection data.
     *
     * @param CustomerAddressAttributeIndex $customerAttributeIndex
     * @param CustomerAddressAttributeNew $customerAttributeNew
     * @param TestStepFactory $testStepFactory
     * @return void
     */
    public function __inject(
        CustomerAddressAttributeIndex $customerAttributeIndex,
        CustomerAddressAttributeNew $customerAttributeNew,
        TestStepFactory $testStepFactory
    ) {
        $this->customerAttributeIndex = $customerAttributeIndex;
        $this->customerAttributeNew = $customerAttributeNew;
        $this->testStepFactory = $testStepFactory;
    }

    /**
     * Execute test.
     *
     * @param CustomerAddressAttribute $customerAddressAttribute
     * @return array
     */
    public function test(CustomerAddressAttribute $customerAddressAttribute)
    {
        $this->addressCustomAttribute = $customerAddressAttribute;

        //Steps
        $this->customerAttributeIndex->open();
        $this->customerAttributeIndex->getGridPageActions()->addNew();
        $this->customerAttributeNew->getCustomerAddressAttributesForm()->fill($customerAddressAttribute);
        $this->customerAttributeNew->getFormPageActions()->save();
    }

    /**
     * Clear data after test.
     *
     * @return void
     */
    protected function tearDown()
    {
        if ($this->addressCustomAttribute === null) {
            return;
        }

        // Remove attribute.
        $this->testStepFactory->create(
            DeleteCustomerAddressAttributeStep::class,
            ['addressCustomAttribute' => $this->addressCustomAttribute]
        )->run();
    }
}
