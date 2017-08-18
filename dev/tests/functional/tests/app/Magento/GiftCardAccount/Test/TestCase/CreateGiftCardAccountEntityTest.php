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
 * 3. Generate new code pool if it is needed (if appropriate error message is displayed on the page).
 * 4. Start to create Gift Card Account.
 * 5. Fill in data according to attached data set.
 * 6. Save Gift Card Account.
 * 7. Perform appropriate assertions.
 *
 * @group Gift_Card
 * @ZephyrId MAGETWO-23865
 */
class CreateGiftCardAccountEntityTest extends Injectable
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
     * Prepare data
     *
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $product = $fixtureFactory->createByCode(
            'catalogProductSimple',
            ['dataset' => 'product_100_dollar']
        );
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
     * Create gift card account entity
     *
     * @param GiftCardAccount $giftCardAccount
     * @return array
     */
    public function test(GiftCardAccount $giftCardAccount)
    {
        // Steps
        $this->giftCardAccountIndex->open();
        $this->giftCardAccountIndex->getMessagesBlock()->clickLinkInMessage('error', 'here');
        $this->giftCardAccountIndex->getGridPageActions()->addNew();
        $this->newIndex->getPageMainForm()->fill($giftCardAccount);
        $this->newIndex->getPageMainActions()->save();

        $code = $this->giftCardAccountIndex->getGiftCardAccount()
            ->getCode(['balance' => $giftCardAccount->getBalance()], false);
        return ['code' => $code];
    }
}
