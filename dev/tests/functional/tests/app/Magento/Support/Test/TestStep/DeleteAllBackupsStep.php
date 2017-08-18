<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Support\Test\TestStep;

use Magento\Support\Test\Page\Adminhtml\SupportBackupIndex;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Support\Test\Constraint\AssertBackupSuccessMassDeleteMessage;
use Magento\Support\Test\Constraint\AssertNoBackupItemInGrid;

/**
 * Deletes all Backups on backend usually called on cleanup after the creating step.
 *
 * Steps:
 * 1. Opens the System > Support > Data Collector page.
 * 2. Performs a mass delete on all elements
 * 3. Verifies the success message
 */
class DeleteAllBackupsStep implements TestStepInterface
{
    /**
     * Backup grid page.
     *
     * @var SupportBackupIndex
     */
    protected $backupIndexPage;

    /**
     * Assert delete message.
     *
     * @var AssertBackupSuccessMassDeleteMessage
     */
    protected $assertMessage;

    /**
     * Assert no item in grid after delete.
     *
     * @var AssertNoBackupItemInGrid
     */
    protected $noItemAssert;

    /**
     * @param SupportBackupIndex $backupIndexPage
     * @param AssertBackupSuccessMassDeleteMessage $assertMessage
     * @param AssertNoBackupItemInGrid $noItemAssert
     */
    public function __construct(
        SupportBackupIndex $backupIndexPage,
        AssertBackupSuccessMassDeleteMessage $assertMessage,
        AssertNoBackupItemInGrid $noItemAssert
    ) {
        $this->backupIndexPage = $backupIndexPage;
        $this->assertMessage = $assertMessage;
        $this->noItemAssert = $noItemAssert;
    }

    /**
     * Delete Backups on backend
     *
     * @return void
     */
    public function run()
    {
        $this->backupIndexPage->open();
        $this->backupIndexPage->getBackupsGridBlock()->resetFilter();
        $this->backupIndexPage->getBackupsGridBlock()->massaction([], 'Delete', true, 'Select All');
        $this->assertMessage->processAssert($this->backupIndexPage);
        $this->noItemAssert->processAssert($this->backupIndexPage);
    }
}
