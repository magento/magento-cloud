<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\GiftRegistry\Test\Page\Adminhtml\GiftRegistryCustomerEdit;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftRegistrySuccessAddedItemsMessage
 * Assert that success message is displayed after adding products to gift registry on backend
 */
class AssertGiftRegistrySuccessAddedItemsMessage extends AbstractConstraint
{
    /**
     * Success added to gift registry message
     */
    const SUCCESS_MESSAGE = 'Shopping cart items have been added to gift registry.';

    /**
     * Assert that success message is displayed after adding products to gift registry on backend
     *
     * @param GiftRegistryCustomerEdit $giftRegistryCustomerEdit
     * @return void
     */
    public function processAssert(GiftRegistryCustomerEdit $giftRegistryCustomerEdit)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_MESSAGE,
            $giftRegistryCustomerEdit->getMessagesBlock()->getSuccessMessage(),
            'Wrong success message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry success message is displayed after adding products to gift registry on backend.';
    }
}
