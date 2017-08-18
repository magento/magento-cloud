<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Constraint;

/**
 * Class AssertCatalogEventIsUpcoming
 * Check event status 'Closed' on category/product pages
 */
class AssertCatalogEventIsClosed extends AssertCatalogEventStatus
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Event status 'Closed' on category/product pages
     *
     * @var string
     */
    protected $eventStatus = 'Closed';
}
