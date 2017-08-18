<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\TestCase;

use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\GiftCardAccount\Test\Page\Adminhtml\Index;
use Magento\GiftCardAccount\Test\Page\Adminhtml\NewIndex;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Steps:
 * 1. Login to the backend.
 * 2. Navigate to Marketing -> Gift Card Accounts.
 * 3. Select required gift card account from preconditions.
 * 4. Click on the "Delete" button.
 * 5. In confirmation popup message with text: "Are you sure you want to do this?" click "OK".
 * 6. Perform appropriate assertions.
 *
 * @group Gift_Card_Account
 * @ZephyrId MAGETWO-24342
 */
class DeleteGiftCardAccountEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const SEVERITY = 'S1';
    /* end tags */

    /**
     * Page of gift card account
     *
     * @var Index
     */
    protected $giftCardAccountIndex;

    /**
     * Page of create gift card account
     *
     * @var NewIndex
     */
    protected $newIndex;

    /**
     * Create gift card account
     *
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $product = $fixtureFactory->createByCode('catalogProductSimple', ['dataset' => 'product_100_dollar']);
        $product->persist();
        $customer = $fixtureFactory->createByCode('customer', ['dataset' => 'default']);
        $customer->persist();
        return [
            'product' => $product,
            'customer' => $customer
        ];
    }

    /**
     * Inject gift card account page
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
     * Delete gift card account entity
     *
     * @param GiftCardAccount $giftCardAccount
     * @return array
     */
    public function testDeleteGiftCardAccount(GiftCardAccount $giftCardAccount)
    {
        $giftCardAccount->persist();
        $this->giftCardAccountIndex->open();
        $code = $giftCardAccount->getCode();
        $this->giftCardAccountIndex->getGiftCardAccount()->searchAndOpen(['code' => $code]);
        $this->newIndex->getPageMainActions()->delete();
        $this->newIndex->getModalBlock()->acceptAlert();
        return ['code' => $code];
    }
}
