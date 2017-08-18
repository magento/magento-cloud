<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountCreate;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that reward points message is displayed on shopping cart page.
 */
class AssertRewardPointsMessageOnShoppingCart extends AbstractConstraint
{
    /**
     * Message about reward points on checkout page.
     */
    const CHECKOUT_REWARD_MESSAGE = 'Check out now and earn %d Reward points for this order.';

    /**
     * Assert that reward points message is displayed on shopping cart page.
     *
     * @param BrowserInterface $browser
     * @param Customer $customer
     * @param CatalogProductSimple $product
     * @param CustomerAccountCreate $customerAccountCreate
     * @param CatalogProductView $productView
     * @param CheckoutCart $checkoutCart
     * @param string $checkoutReward
     */
    public function processAssert(
        BrowserInterface $browser,
        Customer $customer,
        CatalogProductSimple $product,
        CustomerAccountCreate $customerAccountCreate,
        CatalogProductView $productView,
        CheckoutCart $checkoutCart,
        $checkoutReward
    ) {
        $customerAccountCreate->open()->getRegisterForm()->registerCustomer($customer);
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $productView->getViewBlock()->clickAddToCartButton();
        $productView->getMessagesBlock()->waitSuccessMessage();
        $checkoutCart->open();

        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::CHECKOUT_REWARD_MESSAGE, $checkoutReward),
            trim($checkoutCart->getCheckoutTooltipBlock()->getRewardMessage()),
            'Wrong message about checkout reward points is displayed.'
        );
    }

    /**
     * Returns a string representation of successful assertion.
     *
     * @return string
     */
    public function toString()
    {
        return 'Reward points message is appeared on shopping cart page.';
    }
}
