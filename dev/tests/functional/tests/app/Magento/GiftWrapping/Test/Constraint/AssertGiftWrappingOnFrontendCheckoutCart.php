<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountLogout;
use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Assert that Gift Wrapping can be found in checkout cart on frontend.
 */
class AssertGiftWrappingOnFrontendCheckoutCart extends AbstractConstraint
{
    /**
     * Current product.
     *
     * @var FixtureInterface
     */
    protected $product;

    /**
     * Gift wrappings.
     *
     * @var array;
     */
    protected $giftWrapping;

    /**
     * Assert that Gift Wrapping can be found during one page checkout on frontend.
     *
     * @param CatalogProductView $catalogProductView
     * @param CheckoutCart $checkoutCart
     * @param BrowserInterface $browser
     * @param Customer $customer
     * @param CustomerAccountLogout $customerAccountLogout
     * @param FixtureFactory $fixtureFactory
     * @param FixtureInterface $product
     * @param GiftWrapping[] $giftWrapping
     * @return void
     */
    public function processAssert(
        CatalogProductView $catalogProductView,
        CheckoutCart $checkoutCart,
        BrowserInterface $browser,
        Customer $customer,
        CustomerAccountLogout $customerAccountLogout,
        FixtureFactory $fixtureFactory,
        FixtureInterface $product = null,
        $giftWrapping = []
    ) {
        // Preconditions
        $customer->persist();
        $this->prepareProduct($fixtureFactory, $product);
        $this->prepareGiftWrapping($fixtureFactory, $giftWrapping);

        // Steps
        $checkoutCart->open();
        if (!$checkoutCart->getCartBlock()->isProductInShoppingCart($this->product)) {
            $browser->open($_ENV['app_frontend_url'] . $this->product->getData('url_key') . '.html');
            $catalogProductView->getViewBlock()->clickAddToCartButton();
            $catalogProductView->getMessagesBlock()->waitSuccessMessage();
            $checkoutCart->open();
        }

        $giftWrappingsOrderAvailable = $checkoutCart->getGiftOptionsOrderBlock()->getGiftWrappingsAvailable();
        $giftWrappingsItemsAvailable = $checkoutCart->getGiftOptionsItemBlock()->getGiftWrappingsAvailable();
        $matches = [];

        foreach ($this->giftWrapping as $item) {
            if (in_array($item->getDesign(), $giftWrappingsOrderAvailable + $giftWrappingsItemsAvailable)) {
                $matches[] = $item->getDesign();
            }
        }
        $customerAccountLogout->open();
        \PHPUnit_Framework_Assert::assertNotEmpty(
            $matches,
            'Gift Wrapping is not present in shopping cart on frontend.'
            . "\nLog:\n" . implode(";\n", $matches)
        );
    }

    /**
     * Prepare product.
     *
     * @param FixtureFactory $fixtureFactory
     * @param FixtureInterface $product
     */
    private function prepareProduct(FixtureFactory $fixtureFactory, FixtureInterface $product = null)
    {
        if ($product === null) {
            $product = $fixtureFactory->create(CatalogProductSimple::class);
        }

        if (!$product->getData('id')) {
            $product->persist();
        }

        $this->product = $product;
    }

    /**
     * Prepare gift wrappings.
     *
     * @param FixtureFactory $fixtureFactory
     * @param GiftWrapping[] $giftWrapping
     */
    private function prepareGiftWrapping(FixtureFactory $fixtureFactory, $giftWrapping)
    {
        if (empty($giftWrapping)) {
            $giftWrapping = [$fixtureFactory->create(GiftWrapping::class)];
        }

        /** @var FixtureInterface $item */
        foreach ($giftWrapping as $item) {
            if (!$item->getData('wrapping_id')) {
                $item->persist();
            }
        }

        $this->giftWrapping = $giftWrapping;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Wrapping can be found during one page checkout on frontend.';
    }
}
