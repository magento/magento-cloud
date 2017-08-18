<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Rma\Test\Fixture\Rma;
use Magento\Rma\Test\Page\Adminhtml\RmaIndex;
use Magento\Rma\Test\Page\Adminhtml\RmaView;
use Magento\Sales\Test\Fixture\OrderInjectable;

/**
 * Assert that displayed rma data on edit page equals passed from fixture.
 */
class AssertRmaForm extends AbstractAssertRmaOnBackend
{
    /**
     * Assert that displayed rma data on edit page equals passed from fixture.
     *
     * @param Rma $rma
     * @param RmaIndex $rmaIndex
     * @param RmaView $rmaView
     * @return void
     */
    public function processAssert(Rma $rma, RmaIndex $rmaIndex, RmaView $rmaView)
    {
        $this->open($rma, $rmaIndex);
        $fixtureData = $this->getRmaData($rma);
        $pageData = $rmaView->getRmaForm()->getData();

        $this->verifyDetails($fixtureData, $pageData);
        $this->verifyItems($fixtureData['items'], $pageData['items']);
        if (isset($fixtureData['comment'])) {
            $this->verifyComment($fixtureData['comment'], $pageData['comment']);
        }
    }

    /**
     * Return rma data.
     *
     * @param Rma $rma
     * @return array
     */
    protected function getRmaData(Rma $rma)
    {
        /** @var OrderInjectable $order */
        $order = $rma->getDataFieldConfig('order_id')['source']->getOrder();
        $orderItems = $order->getEntityId()['products'];

        if ($order->getData('customer_id')) {
            /** @var Customer $customer */
            $customer = $order->getDataFieldConfig('customer_id')['source']->getCustomer();
            $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
            $customerEmail = $customer->getEmail();
        } else {
            $customerName = 'Guest';
            $customerEmail = $order->getDataFieldConfig('billing_address_id')['source']->getData()['email'];
        }

        $data = $rma->getData();
        $data['customer_name'] = $customerName;
        $data['customer_email'] = $customerEmail;

        foreach ($data['items'] as $key => $item) {
            $product = $orderItems[$key];

            $item['product'] = $product->getName();
            $data['items'][$key] = $item;
        }

        return $data;
    }

    /**
     * Assert that displayed rma details on edit page equals initial data.
     *
     * @param array $fixtureData
     * @param array $pageData
     * @return void
     */
    protected function verifyDetails(array $fixtureData, array $pageData)
    {
        $fixtureDetails = array_diff_key($fixtureData, array_flip($this->skippedFields));
        $pageDetails = array_diff_key($pageData, array_flip($this->skippedFields));

        $errors = $this->verifyData($fixtureDetails, $pageDetails, false, false);
        \PHPUnit_Framework_Assert::assertEmpty(
            $errors,
            'Displayed rma details on edit page does not equals initial data.'
            . "\nLog:\n" . implode(";\n", $errors)
        );
    }

    /**
     * Assert that displayed rma comment on edit page equals initial data.
     *
     * @param array $fixtureComment
     * @param array $pageComments
     * @return void
     */
    protected function verifyComment(array $fixtureComment, array $pageComments)
    {
        $isVisibleComment = false;
        foreach ($pageComments as $pageComment) {
            if ($pageComment['comment'] == $fixtureComment['comment']) {
                $isVisibleComment = true;
            }
        }

        \PHPUnit_Framework_Assert::assertTrue(
            $isVisibleComment,
            'Displayed rma comment on edit page does not equals initial data.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Correct return request is present on backend.';
    }
}
