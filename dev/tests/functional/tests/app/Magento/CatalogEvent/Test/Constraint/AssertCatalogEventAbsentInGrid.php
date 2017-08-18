<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Constraint;

use Magento\CatalogEvent\Test\Fixture\CatalogEventEntity;
use Magento\CatalogEvent\Test\Page\Adminhtml\CatalogEventIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check catalog event is absent in the "Events" grid.
 */
class AssertCatalogEventAbsentInGrid extends AbstractConstraint
{
    /**
     * Assert that catalog event is absent in the "Events" grid.
     *
     * @param CatalogEventEntity $catalogEvent
     * @param CatalogEventIndex $catalogEventIndex
     *
     * @return void
     */
    public function processAssert(
        CatalogEventEntity $catalogEvent,
        CatalogEventIndex $catalogEventIndex
    ) {
        $filter = [
            'category_name' => $catalogEvent->getCategoryId(),
        ];
        $catalogEventIndex->open();
        $catalogEventIndex->getEventGrid()->search($filter);
        \PHPUnit_Framework_Assert::assertFalse(
            $catalogEventIndex->getEventGrid()->isRowVisible($filter, false, false),
            "Event on category " . $catalogEvent->getCategoryId() . " is present in Events grid."
        );
    }

    /**
     * Catalog Event in Event Grid.
     *
     * @return string
     */
    public function toString()
    {
        return 'Catalog Event is absent in Events grid.';
    }
}
