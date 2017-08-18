<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Constraint;

use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Abstract Class AssertMultipleWishlistSuccessSaveMessage
 * Assert success message is displayed
 */
abstract class AbstractAssertMultipleWishlistSuccessMessage extends AbstractConstraint
{
    /**
     * Success message
     *
     * @var string
     */
    protected $message;

    /**
     * Assert success message is displayed
     *
     * @param WishlistIndex $wishlistIndex
     * @param MultipleWishlist $multipleWishlist
     * @return void
     */
    public function processAssert(WishlistIndex $wishlistIndex, MultipleWishlist $multipleWishlist)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf($this->message, $multipleWishlist->getName()),
            $wishlistIndex->getMessagesBlock()->getSuccessMessage(),
            'Wrong success message is displayed.'
        );
    }
}
