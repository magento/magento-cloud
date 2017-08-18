<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Customer\Test\Fixture\Customer;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\SalesRule\Test\Page\Adminhtml\PromoQuoteEdit;
use Magento\SalesRule\Test\Page\Adminhtml\PromoQuoteIndex;

/**
 * Assert Catalog Price Rule discount in shopping cart.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AssertCustomerSegmentPriceRule extends AbstractConstraint
{
    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Page promo Quote Index.
     *
     * @var PromoQuoteIndex
     */
    protected $promoQuoteIndex;

    /**
     * Page promo Quote Edit.
     *
     * @var PromoQuoteEdit
     */
    protected $promoQuoteEdit;

    /**
     * Page CatalogProductView.
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Assert grand total in shopping cart according to Catalog Price Rule configuration.
     *
     * @param FixtureFactory $fixtureFactory
     * @param CheckoutCart $checkoutCart
     * @param CatalogProductSimple $product
     * @param CatalogProductView $catalogProductView
     * @param Customer $customer
     * @param CustomerSegment $customerSegment
     * @param BrowserInterface $browser
     * @param PromoQuoteIndex $promoQuoteIndex
     * @param PromoQuoteEdit $promoQuoteEdit
     * @param array $salesRule
     * @param array $prices
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function processAssert(
        FixtureFactory $fixtureFactory,
        CheckoutCart $checkoutCart,
        CatalogProductSimple $product,
        CatalogProductView $catalogProductView,
        Customer $customer,
        CustomerSegment $customerSegment,
        BrowserInterface $browser,
        PromoQuoteIndex $promoQuoteIndex,
        PromoQuoteEdit $promoQuoteEdit,
        array $salesRule,
        array $prices
    ) {
        // Prepare data
        $this->fixtureFactory = $fixtureFactory;
        $this->catalogProductView = $catalogProductView;
        $this->promoQuoteIndex = $promoQuoteIndex;
        $this->promoQuoteEdit = $promoQuoteEdit;
        $this->createCartPriceRule($salesRule, $customerSegment);
        $product->persist();

        // Assert steps
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $checkoutCart->open();
        $checkoutCart->getCartBlock()->clearShoppingCart();
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $this->catalogProductView->getViewBlock()->clickAddToCart();
        $this->catalogProductView->getMessagesBlock()->waitSuccessMessage();
        $checkoutCart->open();
        $checkoutCart->getCartBlock()->waitCartContainerLoading();
        $checkoutCart->getShippingBlock()->waitForCommonShippingPriceBlock();
        $checkoutCart->getShippingBlock()->waitForUpdatedShippingMethods();
        $checkoutCart->getTotalsBlock()->waitForUpdatedTotals();

        \PHPUnit_Framework_Assert::assertEquals(
            $prices['grandTotal'],
            $checkoutCart->getTotalsBlock()->getGrandTotal(),
            'Grand total in shopping cart is not corresponded to Cart Price Rule configuration.'
        );
    }

    /**
     * Create catalog price rule.
     *
     * @param array $salesRuleData
     * @param CustomerSegment $customerSegment
     * @return void
     */
    protected function createCartPriceRule(array $salesRuleData, CustomerSegment $customerSegment)
    {
        $salesRuleData['conditions_serialized'] = str_replace(
            '%customerSegmentName%',
            $customerSegment->getName(),
            $salesRuleData['conditions_serialized']
        );

        $salesRule = $this->fixtureFactory->createByCode('customerSegmentSalesRule', ['data' => $salesRuleData]);
        $this->promoQuoteIndex->open();
        $this->promoQuoteIndex->getGridPageActions()->addNew();
        $this->promoQuoteEdit->getSalesRuleForm()->fill($salesRule);
        $this->promoQuoteEdit->getFormPageActions()->save();
        $this->promoQuoteIndex->getMessagesBlock()->waitSuccessMessage();
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Grand total in shopping cart is corresponded to Catalog Price Rule configuration.';
    }
}
