<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Eway\Test\TestStep;

use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Checkout\Test\Page\CheckoutOnepageSuccess;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Payment\Test\Fixture\CreditCard;

/**
 * Fill credit card data in eWay frame and place order.
 */
class PlaceOrderWithSharedPagesStep implements TestStepInterface
{
    /**
     * Onepage checkout page.
     *
     * @var CheckoutOnepage
     */
    private $checkoutOnepage;

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Products fixtures.
     *
     * @var array|\Magento\Mtf\Fixture\FixtureInterface[]
     */
    private $products;

    /**
     * Credit card fixture.
     *
     * @var CreditCard
     */
    private $creditCard;

    /**
     * One page checkout sussess page.
     *
     * @var CheckoutOnepageSuccess
     */
    private $checkoutOnepageSuccess;

    /**
     * @param CheckoutOnepage $checkoutOnepage
     * @param CheckoutOnepageSuccess $checkoutOnepageSuccess
     * @param FixtureFactory $fixtureFactory
     * @param CreditCard $creditCard
     * @param array $products
     */
    public function __construct(
        CheckoutOnepage $checkoutOnepage,
        CheckoutOnepageSuccess $checkoutOnepageSuccess,
        FixtureFactory $fixtureFactory,
        CreditCard $creditCard,
        array $products = []
    ) {
        $this->checkoutOnepage = $checkoutOnepage;
        $this->checkoutOnepageSuccess = $checkoutOnepageSuccess;
        $this->fixtureFactory = $fixtureFactory;
        $this->creditCard = $creditCard;
        $this->products = $products;
    }

    /**
     * Fill credit card data in eWay frame and place order.
     *
     * @return array
     */
    public function run()
    {
        $this->checkoutOnepage->getPaymentBlock()->placeOrder();
        $this->checkoutOnepage->getEwaySharedPagesForm()->fill($this->creditCard);
        $orderId = $this->checkoutOnepageSuccess->getSuccessBlock()->getGuestOrderId();
        $order = $this->fixtureFactory->createByCode(
            'orderInjectable',
            [
                'data' => [
                    'id' => $orderId,
                    'entity_id' => ['products' => $this->products]
                ]
            ]
        );

        return ['orderId' => $orderId, 'order' => $order];
    }
}
