<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Constraint;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that success message appears after submitting new return request on frontend for guest.
 */
class AssertRmaSuccessSaveMessageOnFrontend extends AbstractConstraint
{
    /**
     * Rma success save message.
     */
    const SUCCESS_SAVE_MESSAGE = 'You submitted Return #';

    /**
     * Assert that success message appears after submitting new return request on frontend for guest.
     *
     * @param CmsIndex $cmsIndex
     * @return void
     */
    public function processAssert(CmsIndex $cmsIndex)
    {
        $pageMessage = $cmsIndex->getMessagesBlock()->getSuccessMessage();
        \PHPUnit_Framework_Assert::assertContains(
            self::SUCCESS_SAVE_MESSAGE,
            $pageMessage,
            'Wrong success message is displayed.'
            . "\nExpected: " . self::SUCCESS_SAVE_MESSAGE
            . "\nActual: " . $pageMessage
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "Success message appears after submitting new return request on frontend for guest.";
    }
}
