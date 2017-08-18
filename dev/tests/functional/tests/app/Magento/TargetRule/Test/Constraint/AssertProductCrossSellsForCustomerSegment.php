<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\TargetRule\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Fixture\InjectableFixture;
use Magento\Mtf\ObjectManager;

/**
 * Assert that product is displayed in cross-sell section for customer segment.
 */
class AssertProductCrossSellsForCustomerSegment extends AbstractConstraint
{
    /**
     * Assert that product is displayed in cross-sell section for customer segment.
     *
     * @param BrowserInterface $browser
     * @param ObjectManager $objectManager
     * @param FixtureFactory $fixtureFactory
     * @param CheckoutCart $checkoutCart
     * @param CatalogProductSimple $product
     * @param CatalogProductView $catalogProductView
     * @param InjectableFixture[] $promotedProducts
     * @return void
     */
    public function processAssert(
        BrowserInterface $browser,
        ObjectManager $objectManager,
        FixtureFactory $fixtureFactory,
        CheckoutCart $checkoutCart,
        CatalogProductSimple $product,
        CatalogProductView $catalogProductView,
        array $promotedProducts
    ) {
        // Create customer and login for test customer segment
        $customer = $fixtureFactory->createByCode('customer', ['dataset' => 'default']);
        $customer->persist();
        $loginCustomerOnFrontendStep = $objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        );
        $loginCustomerOnFrontendStep->run();

        // Clear cart
        $checkoutCart->open();
        $checkoutCart->getCartBlock()->clearShoppingCart();

        // Check display cross sell products
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $catalogProductView->getViewBlock()->addToCart($product);
        $catalogProductView->getMessagesBlock()->waitSuccessMessage();
        $checkoutCart->open();
        $errors = [];
        foreach ($promotedProducts as $promotedProduct) {
            if (!$checkoutCart->getCrosssellBlock()->getProductItem($promotedProduct)->isVisible()) {
                $errors[] = 'Product \'' . $promotedProduct->getName() . '\' is absent in cross-sell section.';
            }
        }

        // Logout
        $logoutCustomerOnFrontendStep = $objectManager->create(
            \Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep::class
        );
        $logoutCustomerOnFrontendStep->run();

        \PHPUnit_Framework_Assert::assertEmpty($errors, implode(" ", $errors));
    }

    /**
     * Text success product is displayed in cross-sell section.
     *
     * @return string
     */
    public function toString()
    {
        return 'Product is displayed in cross-sell section.';
    }
}
