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
class AssertBackupSuccessSaveMessage extends AbstractConstraint
{
    /**
     * String that has to be present in the success message
     */
    const SUCCESS_MESSAGE = 'The backup has been saved.';

    /**
     * Asserts that the success save message in the backup page equals to the corespondent string
     *
     * @param SupportBackupIndex $backupIndex
     * @return void
     */
    public function processAssert(SupportBackupIndex $backupIndex)
    {
        $actualMessage = $backupIndex->getMessagesBlock()->getSuccessMessage();
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_MESSAGE,
            $actualMessage,
            'Wrong success message is displayed.'
            . "\nExpected: " . self::SUCCESS_MESSAGE
            . "\nActual: " . $actualMessage
        );
    }

    /**
     * Text success save message is displayed
     *
     * @return string
     */
    public function toString()
    {
        return 'Assert that success message is displayed.';
    }
}
