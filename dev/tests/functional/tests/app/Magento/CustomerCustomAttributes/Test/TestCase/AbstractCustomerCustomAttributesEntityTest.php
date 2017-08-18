<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\TestCase;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAttributeIndex;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAttributeNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Parent class for CustomerCustomAttributes tests.
 */
abstract class AbstractCustomerCustomAttributesEntityTest extends Injectable
{
    /**
     * Backend page with the list of customer attributes.
     *
     * @var CustomerAttributeIndex
     */
    protected $customerAttributeIndex;

    /**
     * Backend page with new customer attribute form.
     *
     * @var CustomerAttributeNew
     */
    protected $customerAttributeNew;

    /**
     * Fixture CustomerCustomAttribute.
     *
     * @var CustomerCustomAttribute\null
     */
    protected $customerCustomAttribute = null;

    /**
     * Preparing customer.
     *
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $customer = $fixtureFactory->createByCode('customer', ['dataset' => 'default']);
        $customer->persist();

        return ['customer' => $customer];
    }

    /**
     * Injection data.
     *
     * @param CustomerAttributeIndex $customerAttributeIndex
     * @param CustomerAttributeNew $customerAttributeNew
     * @return void
     */
    public function __inject(
        CustomerAttributeIndex $customerAttributeIndex,
        CustomerAttributeNew $customerAttributeNew
    ) {
        $this->customerAttributeIndex = $customerAttributeIndex;
        $this->customerAttributeNew = $customerAttributeNew;
    }

    /**
     * Clear data after test.
     *
     * @return void
     */
    public function tearDown()
    {
        if ($this->customerCustomAttribute == null) {
            return;
        }
        $filter = ['frontend_label' => $this->customerCustomAttribute->getFrontendLabel()];
        $this->customerAttributeIndex->open();
        $this->customerAttributeIndex->getCustomerCustomAttributesGrid()->searchAndOpen($filter);
        $this->customerAttributeNew->getFormPageActions()->delete();
        $this->customerAttributeNew->getModalBlock()->acceptAlert();
        $this->customerCustomAttribute = null;

        $this->objectManager->create(
            \Magento\CustomerSegment\Test\TestStep\DeleteAllCustomerSegmentsStep::class
        )->run();
    }
}
