<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Mtf\Util\Command\Cli\Cron;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;

/**
 * Assert that temporary product update is still visible after activation
 */
class AssertUpdateVisibleAfterActivation extends AbstractConstraint
{
    /**
     * Assert that temporary product update is visible after activation
     *
     * @param array $updates
     * @param Cron $cron
     * @param CatalogProductEdit $catalogProductEdit
     * @param CatalogProductSimple $product
     * @return void
     */
    public function processAssert(
        array $updates,
        Cron $cron,
        CatalogProductEdit $catalogProductEdit,
        CatalogProductSimple $product
    ) {
        //Each time, the $cron->run() is called, it executes cron:run and sleeps for 60 secs
        // Run cron thrice to force the update
        $cron->run();
        $cron->run();

        //Both the crons above were run at 60 seconds interval
        //Therefore, the last cron was run 60 seconds ago when the product staging update
        //was not yet active. Need to execute this third time,
        //so that cron is executed once the staging update time is reached
        $cron->run();

        $catalogProductEdit->open(['id' => $product->getId()]);

        foreach ($updates as $update) {
            \PHPUnit_Framework_Assert::assertTrue(
                $catalogProductEdit->getProductScheduleBlock()->updateCampaignExists($update->getName()),
                $update->getName() . ' should be visible.'
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
        return 'Product displays the temporary update after it has been activated.';
    }
}
