<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Constraint;

use Magento\Catalog\Test\TestStep\CreateProductsStep;
use Magento\Config\Test\TestStep\SetupConfigurationStep;
use Magento\Customer\Test\Fixture\Address;
use Magento\Customer\Test\Fixture\Customer;
use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepFactory;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Magento\Sales\Test\TestStep\AddProductsStep;
use Magento\Sales\Test\TestStep\FillBillingAddressStep;
use Magento\Tax\Test\TestStep\CreateTaxRuleStep;

/**
 * Assert that gift wrapping totals visibility on order page in backend depends on selected gift wrapping.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AssertGiftWrappingTotalsVisibilityOnBackendOrderCreatePage extends AbstractConstraint
{
    /**
     * Gift Wrapping Totals for Order.
     *
     * @var array
     */
    private $giftWrappingOrderTotals = [
        'Gift Wrapping for Order (Excl. Tax)',
        'Gift Wrapping for Order (Incl. Tax)'
    ];

    /**
     * Steps applied while processing assert.
     *
     * @var TestStepInterface[]
     */
    private $appliedSteps = [];

    /**
     * Assert that gift wrapping totals visibility on order page in backend depends on selected gift wrapping.
     *
     * @param GiftWrapping $giftWrapping
     * @param OrderCreateIndex $orderCreateIndex
     * @param TestStepFactory $testStepFactory
     * @param OrderIndex $orderIndex
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function processAssert(
        GiftWrapping $giftWrapping,
        OrderCreateIndex $orderCreateIndex,
        TestStepFactory $testStepFactory,
        OrderIndex $orderIndex,
        FixtureFactory $fixtureFactory
    ) {
        $orderIndex->open()->getGridPageActions()->addNew();
        // fill order fields for tax to be applied
        $this->fillOrderData($orderCreateIndex, $testStepFactory, $fixtureFactory);

        $giftWrappingBlock = $orderCreateIndex->getGiftOptionsBlock();
        $totalsBlock = $orderCreateIndex->getCreateBlock()->getTotalsBlock();

        $giftWrappingBlock->setGiftWrappingDesign($giftWrapping);
        $giftWrappingBlock->waitPageLoaded();
        
        foreach ($this->giftWrappingOrderTotals as $total) {
            \PHPUnit_Framework_Assert::assertTrue(
                $totalsBlock->isTotalPresent($total),
                sprintf("Order total '%s' is not visible on order create page", $total)
            );
        }

        $giftWrappingBlock->setGiftWrappingDesign(null);
        $giftWrappingBlock->waitPageLoaded();

        foreach ($this->giftWrappingOrderTotals as $total) {
            \PHPUnit_Framework_Assert::assertFalse(
                $totalsBlock->isTotalPresent($total),
                sprintf("Order total '%s' is visible on order create page", $total)
            );
        }

        $this->cleanupSteps();
    }

    /**
     * Fill order data.
     *
     * @param OrderCreateIndex $orderCreateIndex
     * @param TestStepFactory $testStepFactory
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    private function fillOrderData(
        OrderCreateIndex $orderCreateIndex,
        TestStepFactory $testStepFactory,
        FixtureFactory $fixtureFactory
    ) {

        $configData = [
            'enable_gift_message',
            'gift_wrapping_tax_class_taxable_goods',
            'gift_wrapping_display_excluding_including_tax'
        ];
        $configurationStep = $testStepFactory->create(
            SetupConfigurationStep::class,
            [
                'fixtureFactory' => $fixtureFactory,
                'configData' => implode(',', $configData)
            ]
        );
        $configurationStep->run();
        $this->appliedSteps[] = $configurationStep;

        /** @var CreateTaxRuleStep $createTaxRuleStep */
        $createTaxRuleStep = $testStepFactory->create(
            CreateTaxRuleStep::class,
            [
                'fixtureFactory' => $fixtureFactory,
                'taxRule' => 'us_ca_ny_rule'
            ]
        );
        $createTaxRuleStep->run();
        $this->appliedSteps[] = $createTaxRuleStep;

        /** @var Customer $customer */
        $customer = $fixtureFactory->create(Customer::class, ['dataset' => 'default']);
        $orderCreateIndex->getCustomerBlock()->selectCustomer($customer);
        $orderCreateIndex->getStoreBlock()->selectStoreView();

        /** @var CreateProductsStep $createProductStep */
        $createProductStep = $testStepFactory->create(
            CreateProductsStep::class,
            [
                'fixtureFactory' => $fixtureFactory,
                'products' => 'catalogProductSimple::default'
            ]
        );
        $products = $createProductStep->run();
        $this->appliedSteps[] = $createProductStep;

        /** @var AddProductsStep $addProductsStep */
        $addProductsStep = $testStepFactory->create(
            AddProductsStep::class,
            [
                'orderCreateIndex' => $orderCreateIndex,
                'products' => $products['products']
            ]
        );
        $addProductsStep->run();
        $this->appliedSteps[] = $addProductsStep;

        /** @var Address $billingAddress */
        $billingAddress = $fixtureFactory->create(Address::class, ['dataset' => 'US_address_1_without_email']);

        /** @var FillBillingAddressStep $fillBillingStep */
        $fillBillingStep = $testStepFactory->create(
            FillBillingAddressStep::class,
            [
                'orderCreateIndex' => $orderCreateIndex,
                'billingAddress' => $billingAddress
            ]
        );
        $fillBillingStep->run();
        $this->appliedSteps[] = $fillBillingStep;
    }

    /**
     * Run steps cleanup.
     *
     * @return void
     */
    private function cleanupSteps()
    {
        foreach ($this->appliedSteps as $step) {
            if (method_exists($step, 'cleanup')) {
                $step->cleanup();
            }
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift wrapping totals visibility on backend order create page depends on gift wrapping value.';
    }
}
