<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
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
     * @param CatalogProductSimple $product
     * @param CatalogEventIndex $catalogEventIndex
     *
     * @return void
     */
    public function processAssert(
        CatalogProductSimple $product,
        CatalogEventIndex $catalogEventIndex
    ) {
        $categoryName = $product->getCategoryIds()[0];
        $filter = [
            'category_name' => $categoryName,
        ];
        $catalogEventIndex->open();
        $catalogEventIndex->getEventGrid()->search(['category_name' => $filter['category_name']]);
        \PHPUnit_Framework_Assert::assertFalse(
            $catalogEventIndex->getEventGrid()->isRowVisible($filter, false, false),
            "Event on category '$categoryName' is present in Events grid."
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
