<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleData\Test\TestCase;

use Magento\Config\Test\Fixture\ConfigData;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Predefine payment and shipping data.
 *
 * @ticketId MTA-404
 */
class PredefinePaymentShippingTest extends Injectable
{
    /**
     * Predefine payment and shipping data.
     *
     * @param FixtureFactory $fixtureFactory
     * @param string $shippings
     * @param ConfigData $configCurrency
     * @param string $currency
     * @param string $payments
     * @param string $shippingOrigin
     * @return void
     */
    public function test(
        FixtureFactory $fixtureFactory,
        $shippings,
        ConfigData $configCurrency,
        $currency,
        $payments,
        $shippingOrigin
    ) {
        $configCurrency->persist();

        $currencyRate = $this->objectManager->getInstance()->create(
            \Magento\Directory\Test\Fixture\CurrencyRate::class,
            ['dataset' => $currency]
        );
        $currencyRate->persist();

        $shippingData = explode(', ', $shippings);
        $shippingData[] = $shippingOrigin;

        foreach ($shippingData as $value) {
            $configFixture = $fixtureFactory->createByCode('configData', ['dataset' => $value]);
            $configFixture->persist();
        }

        $paymentData = explode(', ', $payments);
        foreach ($paymentData as $value) {
            $configFixture = $fixtureFactory->create(\Magento\Config\Test\Fixture\Config::class);
            $configFixture->switchData($value);
            $configFixture->persist();
        }
    }
}
