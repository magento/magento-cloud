<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Constraint;

/**
 * Class AssertMultipleWishlistSuccessDeleteMessage
 * Assert delete message is displayed
 */
class AssertMultipleWishlistSuccessDeleteMessage extends AbstractAssertMultipleWishlistSuccessMessage
{
    /**
     * Success message
     *
     * @var string
     */
    protected $message = 'Wish List "%s" has been deleted.';

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Multiple Wish List delete message is present.';
    }
}
