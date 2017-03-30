<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\TestCase;

use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\GiftWrapping\Test\Page\Adminhtml\GiftWrappingIndex;
use Magento\GiftWrapping\Test\Page\Adminhtml\GiftWrappingNew;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test Creation for Edit Gift Wrapping
 *
 * Test Flow:
 * Preconditions:
 * 1. Gift Wrapping is created.
 *
 * Steps:
 * 1. Login as admin to backend
 * 2. Navigate to Stores > Other Settings > Gift Wrapping
 * 3. Click on Gift Wrapping in grid
 * 4. Edit fields with data from data set
 * 5. Click 'Save' button
 * 6. Perform all asserts
 *
 * @group Gift_Wrapping_(CS)
 * @ZephyrId MAGETWO-25200
 */
class UpdateGiftWrappingEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'CS';
    /* end tags */

    /**
     * Gift Wrapping grid page
     *
     * @var GiftWrappingIndex
     */
    protected $giftWrappingIndexPage;

    /**
     * Gift Wrapping new/edit page
     *
     * @var GiftWrappingNew
     */
    protected $giftWrappingNewPage;

    /**
     * Injection data
     *
     * @param GiftWrappingIndex $giftWrappingIndexPage
     * @param GiftWrappingNew $giftWrappingNewPage
     * @return void
     */
    public function __inject(GiftWrappingIndex $giftWrappingIndexPage, GiftWrappingNew $giftWrappingNewPage)
    {
        $this->giftWrappingIndexPage = $giftWrappingIndexPage;
        $this->giftWrappingNewPage = $giftWrappingNewPage;
    }

    /**
     * Update Gift Wrapping Entity test
     *
     * @param GiftWrapping $initialGiftWrapping
     * @param GiftWrapping $giftWrapping
     * @return void
     */
    public function test(GiftWrapping $initialGiftWrapping, GiftWrapping $giftWrapping)
    {
        // Precondition
        $initialGiftWrapping->persist();

        // Steps
        $filter = [
            'design' => $initialGiftWrapping->getDesign(),
        ];
        $this->giftWrappingIndexPage->open();
        $this->giftWrappingIndexPage->getGiftWrappingGrid()->searchAndOpen($filter);
        $this->giftWrappingNewPage->getGiftWrappingForm()->fill($giftWrapping);
        $this->giftWrappingNewPage->getFormPageActions()->save();
    }
}
