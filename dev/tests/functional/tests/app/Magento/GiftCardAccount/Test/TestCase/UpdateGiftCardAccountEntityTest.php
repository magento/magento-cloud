<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Customer\Test\Fixture\Customer;
use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\GiftCardAccount\Test\Page\Adminhtml\Index;
use Magento\GiftCardAccount\Test\Page\Adminhtml\NewIndex;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Precondition:
 * 1. Gift Card Account is created.
 *
 * Steps:
 * 1. Login to the backend.
 * 2. Navigate to Marketing -> Gift Card Accounts.
 * 3. Click on the gift card account from grid
 * 4. Edit test value(s) according to dataset.
 * 5. Save Gift Card Account.
 * 6. Perform appropriate assertions.
 *
 * @group Gift_Card_Account
 * @ZephyrId MAGETWO-26665
 */
class UpdateGiftCardAccountEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const SEVERITY = 'S2';
    /* end tags */

    /**
     * Gift Card Account page
     *
     * @var Index
     */
    protected $giftCardAccountIndex;

    /**
     * Gift Card Account create page
     *
     * @var NewIndex
     */
    protected $newIndex;

    /**
     * Fixture factory
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Create gift card account
     *
     * @param CatalogProductSimple $product
     * @param Customer $customer
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(
        CatalogProductSimple $product,
        Customer $customer,
        FixtureFactory $fixtureFactory
    ) {
        $this->fixtureFactory = $fixtureFactory;

        $product->persist();
        $customer->persist();

        return [
            'product' => $product,
            'customer' => $customer
        ];
    }

    /**
     * Inject gift card account pages
     *
     * @param Index $index
     * @param NewIndex $newIndex
     * @return void
     */
    public function __inject(Index $index, NewIndex $newIndex)
    {
        $this->giftCardAccountIndex = $index;
        $this->newIndex = $newIndex;
    }

    /**
     * Update gift card account entity
     *
     * @param GiftCardAccount $giftCardAccountOrigin
     * @param GiftCardAccount $giftCardAccount
     * @return array
     */
    public function test(GiftCardAccount $giftCardAccountOrigin, GiftCardAccount $giftCardAccount)
    {
        // Precondition
        $giftCardAccountOrigin->persist();

        // Steps
        $this->giftCardAccountIndex->open();
        $this->giftCardAccountIndex->getGiftCardAccount()->searchAndOpen(['code' => $giftCardAccountOrigin->getCode()]);
        $this->newIndex->getPageMainForm()->fill($giftCardAccount);
        $this->newIndex->getPageMainActions()->save();

        return [
            'giftCardAccount' => $this->mergeFixture($giftCardAccount, $giftCardAccountOrigin),
            'code' => $giftCardAccountOrigin->getCode()
        ];
    }

    /**
     * Merge Gift Card Account fixture
     *
     * @param GiftCardAccount $giftCardAccount
     * @param GiftCardAccount $giftCardAccountOrigin
     * @return GiftCardAccount
     */
    protected function mergeFixture(GiftCardAccount $giftCardAccount, GiftCardAccount $giftCardAccountOrigin)
    {
        $data = array_merge($giftCardAccountOrigin->getData(), $giftCardAccount->getData());
        return $this->fixtureFactory->createByCode('giftCardAccount', ['data' => $data]);
    }
}
