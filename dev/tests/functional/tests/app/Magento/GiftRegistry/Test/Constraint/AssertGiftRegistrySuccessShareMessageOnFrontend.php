<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftRegistrySuccessShareMessageOnFrontend
 * Assert that after share Gift Registry on frontend successful message appears
 */
class AssertGiftRegistrySuccessShareMessageOnFrontend extends AbstractConstraint
{
    /**
     * Success gift registry share message on frontend
     */
    const SUCCESS_MESSAGE = 'You shared the gift registry for %d emails.';

    /**
     * Assert that success message is displayed after gift registry has been shared on frontend
     *
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param array $recipients
     * @return void
     */
    public function processAssert(GiftRegistryIndex $giftRegistryIndex, $recipients)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::SUCCESS_MESSAGE, count($recipients)),
            $giftRegistryIndex->getMessagesBlock()->getSuccessMessage(),
            'Wrong success message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry success share message is present.';
    }
}
