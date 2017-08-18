<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Constraint;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\Rma\Test\Fixture\Rma;
use Magento\Rma\Test\Page\CustomerAccountRmaIndex;
use Magento\Rma\Test\Page\CustomerAccountRmaView;
use Magento\Sales\Test\Fixture\OrderInjectable;

/**
 * Assert that rma is correct display on frontend (MyAccount - My Returns).
 */
class AssertRmaOnFrontendForCustomer extends AbstractAssertRmaOnFrontend
{
    /**
     * Assert that rma is correct display on frontend (MyAccount - My Returns):
     * - status on rma history page
     * - details and items on rma view page
     *
     * @param Rma $rma
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param CustomerAccountRmaIndex $customerAccountRmaIndex
     * @param CustomerAccountRmaView $customerAccountRmaView
     * @return void
     */
    public function processAssert(
        Rma $rma,
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        CustomerAccountRmaIndex $customerAccountRmaIndex,
        CustomerAccountRmaView $customerAccountRmaView
    ) {
        /** @var OrderInjectable $order */
        $order = $rma->getDataFieldConfig('order_id')['source']->getOrder();
        /** @var Customer $customer */
        $customer = $order->getDataFieldConfig('customer_id')['source']->getCustomer();

        $this->login($customer);
        $cmsIndex->getLinksBlock()->openLink('My Account');
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('My Returns');

        $fixtureRmaStatus = $rma->getStatus();
        $pageRmaData = $customerAccountRmaIndex->getRmaHistory()->getRmaRow($rma)->getData();
        \PHPUnit_Framework_Assert::assertEquals(
            $fixtureRmaStatus,
            $pageRmaData['status'],
            "\nWrong display status of rma."
            . "\nExpected: " . $fixtureRmaStatus
            . "\nActual: " . $pageRmaData['status']
        );

        $customerAccountRmaIndex->getRmaHistory()->getRmaRow($rma)->clickView();
        $pageItemsData = $this->sortDataByPath(
            $customerAccountRmaView->getRmaView()->getRmaItems()->getData(),
            '::product'
        );
        $fixtureItemsData = $this->sortDataByPath(
            $this->getRmaItems($rma),
            '::product'
        );
        foreach ($pageItemsData as $key => $pageItem) {
            $pageItemsData[$key] = array_intersect_key($pageItem, $fixtureItemsData[$key]);
        }
        \PHPUnit_Framework_Assert::assertEquals($fixtureItemsData, $pageItemsData);
    }

    /**
     * Login customer.
     *
     * @param Customer $customer
     * @return void
     */
    protected function login(Customer $customer)
    {
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Correct return request is present on frontend (MyAccount - My Returns).';
    }
}
