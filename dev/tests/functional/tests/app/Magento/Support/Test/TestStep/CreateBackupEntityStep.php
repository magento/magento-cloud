<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Support\Test\TestStep;

use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Support\Test\Page\Adminhtml\SupportBackupIndex;
use Magento\Support\Test\Constraint\AssertBackupSuccessSaveMessage;
use Magento\Support\Test\Constraint\AssertItemInGrid;

/**
 * Creating backup.
 *
 * Steps:
 * 1. Opens the System > Support > Data Collector page.
 * 2. Creates a backup by clicking New backup button on the action bar.
 * 3. Verifies the success message and the grid for the new backup.
 * 4. After scenario completion deletes all backups by mass delete.
 */
class CreateBackupEntityStep implements TestStepInterface
{
    /**
     * Backup Index page.
     *
     * @var SupportBackupIndex
     */
    private $backupIndex;

    /**
     * Delete backups step.
     *
     * @var DeleteAllBackupsStep
     */
    private $deleteAllBackupsStep;

    /**
     * Assertion of item in grid.
     *
     * @var AssertItemInGrid
     */
    private $assertBackupItemInGrid;

    /**
     * Assertion of success message.
     *
     * @var AssertBackupSuccessSaveMessage
     */
    private $assertMessage;

    /**
     * @param SupportBackupIndex $backupIndex
     * @param DeleteAllBackupsStep $deleteAllBackupsStep
     * @param AssertBackupSuccessSaveMessage $assertMessage
     * @param AssertItemInGrid $assertBackupItemInGrid
     */
    public function __construct(
        SupportBackupIndex $backupIndex,
        DeleteAllBackupsStep $deleteAllBackupsStep,
        AssertBackupSuccessSaveMessage $assertMessage,
        AssertItemInGrid $assertBackupItemInGrid
    ) {
        $this->deleteAllBackupsStep = $deleteAllBackupsStep;
        $this->backupIndex = $backupIndex;
        $this->assertMessage = $assertMessage;
        $this->assertBackupItemInGrid = $assertBackupItemInGrid;
    }

    /**
     * Create backup.
     *
     * @return void
     */
    public function run()
    {
        $this->backupIndex->open();
        $existingCount = count($this->backupIndex->getBackupsGridBlock()->getAllIds());
        $this->backupIndex->getPageActionsBlock()->addNew();
        $this->assertMessage->processAssert($this->backupIndex);
        $actualCount = count($this->backupIndex->getBackupsGridBlock()->getAllIds());
        $this->assertBackupItemInGrid->processAssert($existingCount, $actualCount);
    }

    /**
     * Delete all backups.
     *
     * @return void
     */
    public function cleanup()
    {
        $this->deleteAllBackupsStep->run();
    }
}
