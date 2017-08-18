<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Constraint;

use Magento\GiftCardAccount\Test\Page\Adminhtml\Index;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftCardAccountSaveMessage
 * Assert that success message is displayed after gift card account save
 */
class AssertGiftCardAccountSaveMessage extends AbstractConstraint
{
    /**
     * Text value to be checked
     */
    const SUCCESS_MESSAGE = 'You saved the gift card account.';

    /**
     *  Assert that success message is displayed after gift card account save
     *
     * @param Index $index
     * @return void
     */
    public function processAssert(Index $index)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_MESSAGE,
            $index->getMessagesBlock()->getSuccessMessage(),
            'Wrong success message is displayed.'
        );
    }

    /**
     * Text that success message is displayed after gift card account save
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift card account success save message is present.';
    }
}
