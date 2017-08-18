<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Constraint;

use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Customer\Test\Fixture\Customer;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;

/**
 * Matched Customer' tab contains customer according to conditions.
 */
class AssertCustomerSegmentMatchedCustomerWithCart extends AbstractConstraint
{
    /**
     * Login to frontend. Create product. Adding product to shopping cart.
     * Assert that grid on 'Matched Customer' tab contains customer according to conditions(it need save condition
     * before verification), assert number of matched customer near 'Matched Customer(%number%)' should be equal row
     * in grid with adding product to shopping cart.
     *
     * @param BrowserInterface $browser
     * @param CheckoutCart $checkoutCart
     * @param CatalogProductSimple $product
     * @param CatalogProductView $catalogProductView
     * @param Customer $customer
     * @param CustomerSegment $customerSegment
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     * @param AssertCustomerSegmentMatchedCustomer $assertCustomerSegmentMatchedCustomer
     * @return void
     */
    public function processAssert(
        BrowserInterface $browser,
        CheckoutCart $checkoutCart,
        CatalogProductSimple $product,
        CatalogProductView $catalogProductView,
        Customer $customer,
        CustomerSegment $customerSegment,
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerSegmentNew $customerSegmentNew,
        AssertCustomerSegmentMatchedCustomer $assertCustomerSegmentMatchedCustomer
    ) {
        $product->persist();

        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $checkoutCart->open();
        $checkoutCart->getCartBlock()->clearShoppingCart();
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $catalogProductView->getViewBlock()->clickAddToCart();
        $catalogProductView->getMessagesBlock()->waitSuccessMessage();

        $assertCustomerSegmentMatchedCustomer->processAssert(
            $customer,
            $customerSegment,
            $customerSegmentIndex,
            $customerSegmentNew
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer is present in Customer Segment grid. Number of matched customer equals to rows in grid.';
    }
}
