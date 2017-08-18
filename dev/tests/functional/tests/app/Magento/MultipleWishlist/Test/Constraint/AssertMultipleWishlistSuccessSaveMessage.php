<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Constraint;

/**
 * Class AssertMultipleWishlistSuccessSaveMessage
 * Assert success save message is displayed
 */
class AssertMultipleWishlistSuccessSaveMessage extends AbstractAssertMultipleWishlistSuccessMessage
{
    /**
     * Success message
     *
     * @var string
     */
    protected $message = 'Wish list "%s" was saved.';

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Multiple wish list success save message is present.';
    }
}
