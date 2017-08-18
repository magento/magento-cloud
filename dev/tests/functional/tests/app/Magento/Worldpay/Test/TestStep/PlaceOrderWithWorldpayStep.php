<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Worldpay\Test\TestStep;

use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Payment\Test\Fixture\CreditCard;
use Magento\Worldpay\Test\Page\Sandbox\PaymentMethod;
use Magento\Worldpay\Test\Page\Sandbox\PaymentPage;

/**
 * Place order in one page checkout.
 */
class PlaceOrderWithWorldpayStep implements TestStepInterface
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
     * Worldpay payment method page.
     *
     * @var PaymentMethod
     */
    private $paymentMethod;

    /**
     * Worldpay payment page.
     *
     * @var PaymentPage
     */
    private $paymentPage;

    /**
     * Credit card fixture.
     *
     * @var CreditCard
     */
    private $creditCard;

    /**
     * Credit card type.
     * Possible options: VISA, MAESTRO, AMEX, ECMC(Mastercard), DINERS, JCB.
     *
     * @var string
     */
    private $creditCardType;

    /**
     * @param CheckoutOnepage $checkoutOnepage
     * @param FixtureFactory $fixtureFactory
     * @param PaymentMethod $paymentMethod
     * @param PaymentPage $paymentPage
     * @param CreditCard $creditCard
     * @param string $creditCardType
     * @param array $products
     */
    public function __construct(
        CheckoutOnepage $checkoutOnepage,
        FixtureFactory $fixtureFactory,
        PaymentMethod $paymentMethod,
        PaymentPage $paymentPage,
        CreditCard $creditCard,
        $creditCardType,
        array $products = []
    ) {
        $this->checkoutOnepage = $checkoutOnepage;
        $this->fixtureFactory = $fixtureFactory;
        $this->products = $products;
        $this->paymentMethod = $paymentMethod;
        $this->paymentPage = $paymentPage;
        $this->creditCard = $creditCard;
        $this->creditCardType = $creditCardType;
    }

    /**
     * Fill credit card data and place order on Worldpay side.
     *
     * @return array
     */
    public function run()
    {
        $this->checkoutOnepage->getPaymentBlock()->placeOrder();

        $this->paymentMethod->getPurchaseForm()->selectCreditCardType(strtoupper($this->creditCardType));
        $this->paymentPage->getSecurePaymentBlock()->getCardDetailsBlock()->fill($this->creditCard);
        $this->paymentPage->getSecurePaymentBlock()->makePayment();
        $order = $this->fixtureFactory->createByCode(
            'orderInjectable',
            [
                'data' => [
                    'entity_id' => ['products' => $this->products]
                ]
            ]
        );

        return ['order' => $order];
    }
}
