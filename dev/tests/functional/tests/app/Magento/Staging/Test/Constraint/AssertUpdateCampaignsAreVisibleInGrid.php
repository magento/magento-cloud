<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Staging\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Staging\Test\Page\Adminhtml\StagingDashboard;

/**
 * Assert that update campaigns are visible in grid.
 */
class AssertUpdateCampaignsAreVisibleInGrid extends AbstractConstraint
{
    /**
     * Assert that update campaigns are visible in grid.
     *
     * @param StagingDashboard $stagingDashboardPage
     * @param array $updates
     * @return void
     */
    public function processAssert(
        StagingDashboard $stagingDashboardPage,
        array $updates
    ) {
        $stagingDashboardPage->open();

        foreach ($updates as $update) {
            \PHPUnit_Framework_Assert::assertTrue(
                $stagingDashboardPage->getTimelineContent()->hasStaging($update->getName()),
                'The update campaign ' . $update->getName() . ' is not visible in grid.'
            );
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Update campaigns are visible in grid.';
    }
}
