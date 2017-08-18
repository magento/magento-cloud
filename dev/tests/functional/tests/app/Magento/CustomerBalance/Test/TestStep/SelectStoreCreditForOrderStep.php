<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\TestStep;

use Magento\CustomerBalance\Test\Fixture\CustomerBalance;
use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Select store credit on create order page.
 */
class SelectStoreCreditForOrderStep implements TestStepInterface
{
    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Sales order create index page.
     *
     * @var OrderCreateIndex
     */
    protected $orderCreateIndex;

    /**
     * Customer Balance amount.
     *
     * @var string
     */
    private $customerBalance;

    /**
     * Price information.
     *
     * @var array
     */
    private $prices;

    /**
     * Payment information.
     *
     * @var array
     */
    protected $payment;

    /**
     * @param FixtureFactory $fixtureFactory
     * @param OrderCreateIndex $orderCreateIndex
     * @param CustomerBalance $customerBalance
     * @param array $prices
     * @param array $payment
     */
    public function __construct(
        FixtureFactory $fixtureFactory,
        OrderCreateIndex $orderCreateIndex,
        CustomerBalance $customerBalance,
        array $prices,
        array $payment
    ) {
        $this->fixtureFactory = $fixtureFactory;
        $this->orderCreateIndex = $orderCreateIndex;
        $this->customerBalance = $customerBalance;
        $this->prices = $prices;
        $this->payment = $payment;
    }

    /**
     * Select store credit on create order page.
     *
     * @return array|null
     */
    public function run()
    {
        if (!isset($this->payment['use_customer_balance'])) {
            return null;
        }
        $this->orderCreateIndex->getStoreCreditBlock()->fillStoreCredit($this->payment);
        $customerBalanceData = $this->customerBalance->getData();
        $customer = $this->customerBalance->getDataFieldConfig('customer_id')['source']->getCustomer();
        $website = $this->customerBalance->getDataFieldConfig('website_id')['source']->getWebsite();
        $data = [
            'balance_delta' => $this->customerBalance->getBalanceDelta() - $this->prices['storeCredit'],
            'customer_id' => ['customer' => $customer],
            'website_id' => ['website' => $website]
        ];
        $customerBalance = $this->fixtureFactory->createByCode(
            'customerBalance',
            ['data' => array_merge($customerBalanceData, $data)]
        );

        return ['customerBalance' => $customerBalance];
    }
}
