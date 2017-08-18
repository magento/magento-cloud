<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Support\Test\TestStep;

use Magento\Support\Test\Page\Adminhtml\SupportReportIndex;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Support\Test\Constraint\AssertReportSuccessMassDeleteMessage;
use Magento\Support\Test\Constraint\AssertNoReportItemInGrid;

/**
 * Deletes all Reports on backend usually called on cleanup after the creating step.
 *
 * Steps:
 * 1. Opens the  System > Support > System Report.
 * 2. Performs a mass delete on all elements.
 * 3. Verifies the success message.
 */
class DeleteAllReportsStep implements TestStepInterface
{
    /**
     * Report grid page.
     *
     * @var SupportReportIndex
     */
    protected $reportIndexPage;

    /**
     * Assert delete message
     *
     * @var AssertReportSuccessMassDeleteMessage
     */
    protected $assertMessage;

    /**
     * Assert no item in grid after delete.
     *
     * @var AssertNoReportItemInGrid
     */
    protected $noItemAssert;
    
    /**
     * @param SupportReportIndex $reportIndexPage
     * @param AssertReportSuccessMassDeleteMessage $assertMessage
     * @param AssertNoReportItemInGrid $noItemAssert
     */
    public function __construct(
        SupportReportIndex $reportIndexPage,
        AssertReportSuccessMassDeleteMessage $assertMessage,
        AssertNoReportItemInGrid $noItemAssert
    ) {
        $this->reportIndexPage = $reportIndexPage;
        $this->assertMessage = $assertMessage;
        $this->noItemAssert = $noItemAssert;
    }

    /**
     * Delete Reports on backend.
     *
     * @return void
     */
    public function run()
    {
        $this->reportIndexPage->open();
        $this->reportIndexPage->getReportsGridBlock()->resetFilter();
        $this->reportIndexPage->getReportsGridBlock()->massaction([], 'Delete', true, 'Select All');
        $this->assertMessage->processAssert($this->reportIndexPage);
        $this->noItemAssert->processAssert($this->reportIndexPage);
    }
}
