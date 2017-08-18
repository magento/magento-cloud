<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Test\Constraint;

use Magento\Support\Test\Page\Adminhtml\SupportBackupIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assertion to check Success Save Message for Backup.
 */
class AssertBackupSuccessMassDeleteMessage extends AbstractConstraint
{
    /**
     * Partial string that has to be present in the success message
     */
    const SUCCESS_MESSAGE = 'have been deleted.';

    /**
     * Asserts that the success message includes the corespondent string
     *
     * @param SupportBackupIndex $backupIndex
     * @return void
     */
    public function processAssert(SupportBackupIndex $backupIndex)
    {
        $actualMessage = $backupIndex->getMessagesBlock()->getSuccessMessage();
        \PHPUnit_Framework_Assert::assertContains(
            self::SUCCESS_MESSAGE,
            $actualMessage,
            'Wrong success message is displayed.'
            . "\nExpected: " . self::SUCCESS_MESSAGE
            . "\nActual: " . $actualMessage
        );
    }

    /**
     * Text to indicate mass deletion success
     *
     * @return string
     */
    public function toString()
    {
        return 'Asserting that elements from backup grid have been deleted.';
    }
}
