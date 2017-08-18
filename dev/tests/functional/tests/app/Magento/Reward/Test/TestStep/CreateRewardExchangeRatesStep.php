<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\TestStep;

use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Create reward exchange rates.
 */
class CreateRewardExchangeRatesStep implements TestStepInterface
{
    /**
     * Factory for Fixture.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Array with reward rates dataset names.
     *
     * @var array
     */
    protected $rewardRates;

    /**
     * Delete all reward rates step.
     *
     * @var array
     */
    protected $deleteAllRewardRates;

    /**
     * Preparing step properties.
     *
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param DeleteAllRewardRatesStep $deleteAllRewardRates
     * @param array|null $rewardRates
     */
    public function __construct(
        FixtureFactory $fixtureFactory,
        DeleteAllRewardRatesStep $deleteAllRewardRates,
        array $rewardRates = []
    ) {
        $this->fixtureFactory = $fixtureFactory;
        $this->rewardRates = $rewardRates;
        $this->deleteAllRewardRates = $deleteAllRewardRates;
    }

    /**
     * Create reward exchange rates.
     *
     * @return void
     */
    public function run()
    {
        if (!empty($this->rewardRates)) {
            foreach ($this->rewardRates as $rewardRate) {
                $exchangeRate = $this->fixtureFactory->createByCode('rewardRate', ['dataset' => $rewardRate]);
                $exchangeRate->persist();
            }
        }
    }

    /**
     * Delete all reward rates.
     *
     * @return void
     */
    public function cleanup()
    {
        if (!empty($this->rewardRates)) {
            $this->deleteAllRewardRates->run();
        }
    }
}
