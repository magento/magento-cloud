<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\TestCase;

use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount\WebsiteId;
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
 * @group Gift_Card_(CS)
 * @ZephyrId MAGETWO-23865
 */
class CreateGiftCardAccountEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'CS';
    /* end tags */

    /**
     * Page of gift card account.
     *
     * @var Index
     */
    protected $giftCardAccountIndex;

    /**
     * Page of create gift card account.
     *
     * @var NewIndex
     */
    protected $newIndex;

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Prepare data.
     *
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $this->fixtureFactory = $fixtureFactory;

        $product = $this->fixtureFactory->createByCode(
            'catalogProductSimple',
            ['dataset' => 'product_100_dollar']
        );
        $product->persist();

        return ['product' => $product];
    }

    /**
     * Inject gift card account page.
     *
     * @param Index $index
     * @param NewIndex $newIndex
     * @return array
     */
    public function __inject(Index $index, NewIndex $newIndex)
    {
        $this->giftCardAccountIndex = $index;
        $this->newIndex = $newIndex;

        $customer = $this->fixtureFactory->createByCode('customer', ['dataset' => 'default']);
        $customer->persist();

        return ['customer' => $customer];
    }

    /**
     * Create gift card account entity.
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

        return $this->prepareGiftCardAccount($giftCardAccount);
    }

    /**
     * Prepare gift card.
     *
     * @param GiftCardAccount $giftCardAccountOrig
     * @return GiftCardAccount
     */
    private function prepareGiftCardAccount(GiftCardAccount $giftCardAccountOrig)
    {
        $row = $this->giftCardAccountIndex->getGiftCardAccount()->getNewestRowData(
            ['giftcardaccount_id', 'code']
        );

        $website = null;
        $websiteSource = $giftCardAccountOrig->getDataFieldConfig('website_id')['source'];

        if (is_a($websiteSource, WebsiteId::class) && $websiteSource->getWebsite()) {
            $website = $websiteSource->getWebsite();
        }

        $data = array_merge(
            $giftCardAccountOrig->getData(),
            [
                'code' => $row['code'],
                'id' => $row['giftcardaccount_id'],
                'website_id' => ['source' => $website]
            ]
        );

        $giftCardAccount = $this->fixtureFactory->create(GiftCardAccount::class, ['data' => $data]);

        return [
            'code' => $row['code'],
            'giftCardAccount' => $giftCardAccount
        ];
    }
}
