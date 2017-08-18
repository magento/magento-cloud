<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\TestStep;

use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Class CreateGiftCardAccountStep
 * Creating gift card account
 */
class CreateGiftCardAccountStep implements TestStepInterface
{
    /**
     * Gift card account name in data set
     *
     * @var string
     */
    protected $giftCardAccount;

    /**
     * Factory for Fixture
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Preparing step properties
     *
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param string|null $giftCardAccount
     */
    public function __construct(FixtureFactory $fixtureFactory, $giftCardAccount = null)
    {
        $this->fixtureFactory = $fixtureFactory;
        $this->giftCardAccount = $giftCardAccount;
    }

    /**
     * Creating sales rule
     *
     * @return array
     */
    public function run()
    {
        $result['giftCardAccount'] = null;
        if ($this->giftCardAccount !== null) {
            $giftCardAccount = $this->fixtureFactory->createByCode(
                'giftCardAccount',
                ['dataset' => $this->giftCardAccount]
            );
            $giftCardAccount->persist();
            $result['giftCardAccount'] = $giftCardAccount;
        }

        return $result;
    }
}
