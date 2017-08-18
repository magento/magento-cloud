<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Constraint;

use Magento\CatalogEvent\Test\Page\Adminhtml\CatalogEventIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCatalogEventSuccessCreateMessage
 * Check present delete message on Event page
 */
class AssertCatalogEventSuccessDeleteMessage extends AbstractConstraint
{
    const DELETE_MESSAGE = 'You deleted the event.';

    /**
     * Assert that message "You deleted the event." is present on Event page
     *
     * @param CatalogEventIndex $catalogEventIndex
     * @return void
     */
    public function processAssert(CatalogEventIndex $catalogEventIndex)
    {
        $actualMessage = $catalogEventIndex->getMessagesBlock()->getSuccessMessage();
        \PHPUnit_Framework_Assert::assertEquals(
            self::DELETE_MESSAGE,
            $actualMessage,
            'Wrong message is displayed.'
            . "\nExpected: " . self::DELETE_MESSAGE
            . "\nActual: " . $actualMessage
        );
    }

    /**
     * Text success present save message
     *
     * @return string
     */
    public function toString()
    {
        return 'Event delete message is present.';
    }
}
