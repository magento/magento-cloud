<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\TestStep;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerAddressAttribute;
use Magento\Mtf\TestStep\TestStepFactory;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Create customer address attribute step.
 */
class CreateCustomerAddressAttributeStep implements TestStepInterface
{
    /**
     * Customer Address Attribute.
     *
     * @var CustomerAddressAttribute
     */
    private $customAttribute;

    /**
     * Test step factory.
     *
     * @var TestStepFactory
     */
    private $testStepFactory;

    /**
     * @constructor
     * @param CustomerAddressAttribute $customAttribute
     * @param TestStepFactory $testStepFactory
     */
    public function __construct(CustomerAddressAttribute $customAttribute, TestStepFactory $testStepFactory)
    {
        $this->customAttribute = $customAttribute;
        $this->testStepFactory = $testStepFactory;
    }

    /**
     * Create customer account.
     *
     * @return array
     */
    public function run()
    {
        $this->customAttribute->persist();

        return ['customAttribute' => $this->customAttribute];
    }

    /**
     * Delete customer address attribute.
     *
     * @return void
     */
    public function cleanup()
    {
        $this->testStepFactory
            ->create(DeleteCustomerAddressAttributeStep::class, ['customAttribute' => $this->customAttribute])
            ->run();
    }
}
