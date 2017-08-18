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
 * Check present success message on Event page
 */
class AssertCatalogEventSuccessCreateMessage extends AbstractConstraint
{
    const SUCCESS_MESSAGE = 'You saved the event.';

    /**
     * Assert that message "You saved the event." is present on Event page
     *
     * @param CatalogEventIndex $catalogEventIndex
     * @return void
     */
    public function processAssert(CatalogEventIndex $catalogEventIndex)
    {
        $actualMessage = $catalogEventIndex->getMessagesBlock()->getSuccessMessage();
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_MESSAGE,
            $actualMessage,
            'Wrong success message is displayed.'
            . "\nExpected: " . self::SUCCESS_MESSAGE
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
        return 'Event success save message is present.';
    }
}
