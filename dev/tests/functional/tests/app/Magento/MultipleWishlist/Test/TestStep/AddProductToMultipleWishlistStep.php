<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\TestStep;

use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Fixture\InjectableFixture;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Add product to multiple wishlist.
 */
class AddProductToMultipleWishlistStep implements TestStepInterface
{
    /**
     * Injectable fixture.
     *
     * @var InjectableFixture
     */
    protected $product;

    /**
     * Browser.
     *
     * @var BrowserInterface
     */
    protected $browser;

    /**
     * CatalogProductView page.
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * MultipleWishlist fixture.
     *
     * @var MultipleWishlist
     */
    protected $multipleWishlist;

    /**
     * CustomerAccountIndex page.
     *
     * @var CustomerAccountIndex
     */
    protected $customerAccountIndex;

    /**
     * Variable that determines whether to add product to wish list for the second time or not.
     *
     * @var string
     */
    protected $duplicate;

    /**
     * @constructor
     * @param InjectableFixture $product
     * @param BrowserInterface $browser
     * @param CatalogProductView $catalogProductView
     * @param MultipleWishlist $multipleWishlist
     * @param CustomerAccountIndex $customerAccountIndex
     * @param string $duplicate
     */
    public function __construct(
        InjectableFixture $product,
        BrowserInterface $browser,
        CatalogProductView $catalogProductView,
        MultipleWishlist $multipleWishlist,
        CustomerAccountIndex $customerAccountIndex,
        $duplicate
    ) {
        $this->product = $product;
        $this->browser = $browser;
        $this->duplicate = $duplicate;
        $this->catalogProductView = $catalogProductView;
        $this->multipleWishlist = $multipleWishlist;
        $this->customerAccountIndex = $customerAccountIndex;
    }

    /**
     * Add product to multiple wish list.
     *
     * @return void
     */
    public function run()
    {
        $this->addToMultipleWishlist();
        if ($this->duplicate == 'yes') {
            $this->addToMultipleWishlist();
        }
    }

    /**
     * Add product to multiple wish list.
     *
     * @return void
     */
    protected function addToMultipleWishlist()
    {
        $this->browser->open($_ENV['app_frontend_url'] . $this->product->getUrlKey() . '.html');
        $this->catalogProductView->getViewBlock()->fillOptions($this->product);
        $checkoutData = $this->product->getCheckoutData();
        if (isset($checkoutData['qty'])) {
            $qty = $this->duplicate === 'yes'
                ? $checkoutData['qty'] / 2
                : $checkoutData['qty'];
            $this->catalogProductView->getViewBlock()->setQty($qty);
        }
        $this->catalogProductView->getMultipleWishlistViewBlock()->addToMultipleWishlist($this->multipleWishlist);
        $this->customerAccountIndex->getMessages()->waitSuccessMessage();
    }
}
