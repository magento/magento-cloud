<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\TestCase;

use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountLogin;
use Magento\Customer\Test\Page\CustomerAccountLogout;
use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create Customer
 * 2. Create GiftCard account
 *
 * Steps:
 * 1. Go to frontend
 * 2. Login as a Customer if Customer Name is specified in Data Set
 * 3. Add Product (according to dataset) to the Cart
 * 4. Expand Gift Cards tab and fill code
 * 5. Click Add Gift Card
 * 6. Perform appropriate assertions
 *
 * @group Gift_Card_Account
 * @ZephyrId MAGETWO-28388
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AddGiftCardInShoppingCartTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const SEVERITY = 'S1';
    /* end tags */

    /**
     * Fixture Factory
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * CmsIndex Page
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * CustomerAccountLogin Page on frontend
     *
     * @var CustomerAccountLogin
     */
    protected $customerAccountLogin;

    /**
     * CatalogProductView Page
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * CheckoutCart Page
     *
     * @var CheckoutCart
     */
    protected $checkoutCart;

    /**
     * Customer account logout on frontend
     *
     * @var CustomerAccountLogout
     */
    protected $customerAccountLogout;

    /**
     * Create customer and gift card account
     *
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $customer = $fixtureFactory->createByCode('customer', ['dataset' => 'default']);
        $customer->persist();

        $giftCardAccount = $fixtureFactory->createByCode('giftCardAccount', ['dataset' => 'active_redeemable_account']);
        $giftCardAccount->persist();

        return [
            'customerFixture' => $customer,
            'giftCardAccount' => $giftCardAccount,
        ];
    }

    /**
     * Injection data
     *
     * @param FixtureFactory $fixtureFactory
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountLogin $customerAccountLogin
     * @param CatalogProductView $catalogProductView
     * @param CheckoutCart $checkoutCart
     * @param CustomerAccountLogout $customerAccountLogout
     * @return void
     */
    public function __inject(
        FixtureFactory $fixtureFactory,
        CmsIndex $cmsIndex,
        CustomerAccountLogin $customerAccountLogin,
        CatalogProductView $catalogProductView,
        CheckoutCart $checkoutCart,
        CustomerAccountLogout $customerAccountLogout
    ) {
        $this->fixtureFactory = $fixtureFactory;
        $this->cmsIndex = $cmsIndex;
        $this->customerAccountLogin = $customerAccountLogin;
        $this->catalogProductView = $catalogProductView;
        $this->checkoutCart = $checkoutCart;
        $this->customerAccountLogout = $customerAccountLogout;
    }

    /**
     * Add GiftCard in ShoppingCart
     *
     * @param Customer $customerFixture
     * @param GiftCardAccount $giftCardAccount
     * @param BrowserInterface $browser
     * @param string $product
     * @param string $customer
     * @return array
     */
    public function test(
        Customer $customerFixture,
        GiftCardAccount $giftCardAccount,
        BrowserInterface $browser,
        $product,
        $customer
    ) {
        // Preconditions
        list($fixture, $dataset) = explode('::', $product);
        $product = $this->fixtureFactory->createByCode($fixture, ['dataset' => $dataset]);
        $product->persist();

        // Steps
        $this->cmsIndex->open();
        if ($customer !== '-') {
            $this->cmsIndex->getLinksBlock()->openLink("Sign In");
            $this->customerAccountLogin->getLoginBlock()->login($customerFixture);
        }
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $this->catalogProductView->getViewBlock()->addToCart($product);
        $this->catalogProductView->getMessagesBlock()->waitSuccessMessage();
        $this->checkoutCart->open();
        $this->checkoutCart->getGiftCardAccountBlock()->addGiftCard($giftCardAccount->getCode());

        return ['giftCardAccount' => $giftCardAccount];
    }

    /**
     * Logout customer from frontend account
     *
     * @return void
     */
    public function tearDown()
    {
        $this->customerAccountLogout->open();
    }
}
