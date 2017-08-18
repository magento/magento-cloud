<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Staging\Test\Fixture\Update;

/**
 * Assert that product has correct update campaigns with correct time frames.
 */
class AssertProductUpdateInfoCorrect extends AbstractConstraint
{
    /**
     * Assert that product has correct update campaigns with correct time frames.
     *
     * @param CatalogProductEdit $catalogProductEdit
     * @param CatalogProductSimple $product
     * @param array $updates
     * @return void
     */
    public function processAssert(
        CatalogProductEdit $catalogProductEdit,
        CatalogProductSimple $product,
        array $updates
    ) {
        $catalogProductEdit->open(['id' => $product->getId()]);

        foreach ($updates as $update) {
            $this->verifyUpdateExists($update, $catalogProductEdit);
            $this->verifyStartTime($update, $catalogProductEdit);
            if ($update->getEndTime()) {
                $this->verifyEndTime($update, $catalogProductEdit);
            }
        }
    }

    /**
     * Verify that product update campaign start date is correct.
     *
     * @param Update $stage
     * @param CatalogProductEdit $catalogProductEdit
     * @return void
     */
    private function verifyStartTime(Update $stage, CatalogProductEdit $catalogProductEdit)
    {
        $actualStartTime = implode(' ', $catalogProductEdit->getProductScheduleBlock()->getStartDate($stage));
        $date = new \DateTime();
        $date->setTimestamp(strtotime($stage->getStartTime()));
        if (!$stage->getDataFieldConfig('start_time')['source']->isTimezoneApplied()) {
            $date->setTimezone(new \DateTimeZone($_ENV['magento_timezone']));
        }
        \PHPUnit_Framework_Assert::assertEquals(
            $date->format('M j, Y g:i A'),
            $actualStartTime,
            'Product update campaign start date is incorrect.'
        );
    }

    /**
     * Verify that product update campaign end date is correct.
     *
     * @param Update $stage
     * @param CatalogProductEdit $catalogProductEdit
     * @return void
     */
    private function verifyEndTime(Update $stage, CatalogProductEdit $catalogProductEdit)
    {
        $actualEndTime = implode(' ', $catalogProductEdit->getProductScheduleBlock()->getEndDate($stage));
        $date = new \DateTime();
        $date->setTimestamp(strtotime($stage->getEndTime()));
        if (!$stage->getDataFieldConfig('end_time')['source']->isTimezoneApplied()) {
            $date->setTimezone(new \DateTimeZone($_ENV['magento_timezone']));
        }
        \PHPUnit_Framework_Assert::assertSame(
            $date->format('M j, Y g:i A'),
            $actualEndTime,
            'Product update campaign end date is incorrect.'
        );
    }

    /**
     * Verify that product update campaign exists.
     *
     * @param Update $stage
     * @param CatalogProductEdit $catalogProductEdit
     * @return void
     */
    private function verifyUpdateExists(Update $stage, CatalogProductEdit $catalogProductEdit)
    {
        \PHPUnit_Framework_Assert::assertTrue(
            $catalogProductEdit->getProductScheduleBlock()->updateCampaignExists($stage->getName()),
            'Product update campaign does not exist or has an incorrect name.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Product update campaign info is correct.';
    }
}
