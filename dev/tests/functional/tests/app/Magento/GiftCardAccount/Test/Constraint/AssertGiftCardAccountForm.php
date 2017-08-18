<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Constraint;

use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\GiftCardAccount\Test\Page\Adminhtml\Index;
use Magento\GiftCardAccount\Test\Page\Adminhtml\NewIndex;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Class AssertGiftCardAccountForm
 * Assert that gift card account equals to passed from fixture
 */
class AssertGiftCardAccountForm extends AbstractAssertForm
{
    /**
     * Skipped fields for verify data
     *
     * @var array
     */
    protected $skippedFields = ['code'];

    /**
     * Assert that gift card account equals to passed from fixture
     *
     * @param GiftCardAccount $giftCardAccount
     * @param Index $index
     * @param NewIndex $newIndex
     * @return void
     */
    public function processAssert(
        GiftCardAccount $giftCardAccount,
        Index $index,
        NewIndex $newIndex
    ) {
        $index->open();
        $filter = ['balance' => $giftCardAccount->getBalance()];
        $index->getGiftCardAccount()->searchAndOpen($filter, false);

        $giftCardAccountFormData = $newIndex->getPageMainForm()->getData();
        $dataDiff = $this->verifyData($giftCardAccount->getData(), $giftCardAccountFormData);

        \PHPUnit_Framework_Assert::assertEmpty(
            $dataDiff,
            "Gift card account form data does not equal to passed from fixture. \n" . $dataDiff
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift card account form data equals to passed from fixture.';
    }
}
