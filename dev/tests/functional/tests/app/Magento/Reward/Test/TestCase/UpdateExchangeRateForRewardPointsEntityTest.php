<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Config\Test\Fixture\ConfigData;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Reward\Test\Fixture\RewardRate;
use Magento\Reward\Test\Page\Adminhtml\RewardRateIndex;
use Magento\Reward\Test\Page\Adminhtml\RewardRateNew;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Product is created.
 * 2. Register new customer.
 * 3. Reward exchange rates is created.
 *
 * Steps:
 * 1. Login to the backend.
 * 2. Navigate to Stores > Other Settings > Reward Exchange Rates.
 * 3. Click on the "Points > Currency" exchange rate from preconditions.
 * 4. Fill in data according to attached data set.
 * 5. Save Reward Exchange Rate.
 * 6. Perform appropriate assertions.
 *
 * @group Reward_Points
 * @ZephyrId MAGETWO-26628
 */
class UpdateExchangeRateForRewardPointsEntityTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    const SEVERITY = 'S2';
    /* end tags */

    /**
     * Index page reward exchange rates.
     *
     * @var RewardRateIndex
     */
    protected $rewardRateIndexPage;

    /**
     * Page new reward exchange rate.
     *
     * @var RewardRateNew
     */
    protected $rewardRateNewPage;

    /**
     * Configuration rollback data set.
     *
     * @var ConfigData
     */
    protected $configRollback;

    /**
     * Prepare data.
     *
     * @param CatalogProductSimple $product
     * @return array
     */
    public function __prepare(CatalogProductSimple $product)
    {
        $product->persist();
        $this->objectManager->create(\Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep::class)->run();

        return ['product' => $product];
    }

    /**
     * Injection data.
     *
     * @param RewardRateIndex $rewardRateIndexPage
     * @param RewardRateNew $rewardRateNewPage
     * @return void
     */
    public function __inject(
        RewardRateIndex $rewardRateIndexPage,
        RewardRateNew $rewardRateNewPage
    ) {
        $this->rewardRateIndexPage = $rewardRateIndexPage;
        $this->rewardRateNewPage = $rewardRateNewPage;
    }

    /**
     * Run update exchange rate reward points entity test.
     *
     * @param RewardRate $originalRate
     * @param RewardRate $updateRate
     * @param ConfigData $config
     * @param ConfigData $configRollback
     * @return void
     */
    public function test(
        RewardRate $originalRate,
        RewardRate $updateRate,
        ConfigData $config,
        ConfigData $configRollback
    ) {
        // Precondition
        $this->configRollback = $configRollback;
        $config->persist();
        $originalRate->persist();

        // Steps
        $this->rewardRateIndexPage->open();
        $this->rewardRateIndexPage->getRewardRateGrid()->searchAndOpen(['rate_id' => $originalRate->getRateId()]);
        $this->rewardRateNewPage->getRewardRateForm()->fill($updateRate);
        $this->rewardRateNewPage->getFormPageActions()->save();
    }

    /**
     * Clear magento instance after test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(\Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep::class)->run();
        $this->objectManager->create(\Magento\Reward\Test\TestStep\DeleteAllRewardRatesStep::class)->run();
        $this->configRollback->persist();
    }
}
