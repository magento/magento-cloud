<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Support\Test\TestStep;

use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Support\Test\Page\Adminhtml\SupportReportIndex;
use Magento\Support\Test\Page\Adminhtml\SupportReportNew;
use Magento\Support\Test\Fixture\SupportReport;
use Magento\Support\Test\Constraint\AssertReportSuccessSaveMessage;
use Magento\Support\Test\Constraint\AssertItemInGrid;

/**
 * Creating report.
 *
 * Steps:
 * 1. Opens the  System > Support > System Report page.
 * 2. Creates a report by clicking New report button on the action bar.
 * 3. Opens the Create System Report page and clicks on Create and generates all reports from the multiselect.
 * 3. Verifies the success message and the grid for the new report.
 * 4. After scenario completion deletes all reports by mass delete.
 */
class CreateReportEntityStep implements TestStepInterface
{
    /**
     * Report Index page.
     *
     * @var SupportReportIndex
     */
    private $reportIndex;

    /**
     * New Report page.
     *
     * @var SupportReportNew
     */
    private $reportNew;

    /**
     * Report fixture.
     *
     * @var SupportReport
     */
    private $supportReport;

    /**
     * Delete reports step.
     *
     * @var DeleteAllReportsStep
     */
    private $deleteAllReportsStep;

    /**
     * Assertion of item in grid
     *
     * @var AssertItemInGrid
     */
    private $assertReportItemInGrid;

    /**
     * Assertion of success message.
     *
     * @var DeleteAllReportsStep
     */
    private $assertMessage;

    /**
     * @param SupportReportIndex $reportIndex
     * @param SupportReportNew $reportNew
     * @param SupportReport $supportReport
     * @param DeleteAllReportsStep $deleteAllReportsStep
     * @param AssertReportSuccessSaveMessage $assertMessage
     * @param AssertItemInGrid $assertReportItemInGrid
     */
    public function __construct(
        SupportReportIndex $reportIndex,
        SupportReportNew $reportNew,
        SupportReport $supportReport,
        DeleteAllReportsStep $deleteAllReportsStep,
        AssertReportSuccessSaveMessage $assertMessage,
        AssertItemInGrid $assertReportItemInGrid
    ) {
        $this->reportIndex = $reportIndex;
        $this->reportNew = $reportNew;
        $this->supportReport = $supportReport;
        $this->deleteAllReportsStep = $deleteAllReportsStep;
        $this->assertMessage = $assertMessage;
        $this->assertReportItemInGrid = $assertReportItemInGrid;
    }

    /**
     * Create report.
     *
     * @return void
     */
    public function run()
    {
        $this->reportIndex->open();
        $existingCount = count($this->reportIndex->getReportsGridBlock()->getAllIds());
        $this->reportIndex->getPageActionsBlock()->addNew();
        $this->reportNew->getReportForm()->fill($this->supportReport);
        $this->reportNew->getFormPageActions()->save();
        $this->assertMessage->processAssert($this->reportIndex);
        $actualCount = count($this->reportIndex->getReportsGridBlock()->getAllIds());
        $this->assertReportItemInGrid->processAssert($existingCount, $actualCount);
    }

    /**
     * Delete all reports.
     *
     * @return void
     */
    public function cleanup()
    {
        $this->deleteAllReportsStep->run();
    }
}
