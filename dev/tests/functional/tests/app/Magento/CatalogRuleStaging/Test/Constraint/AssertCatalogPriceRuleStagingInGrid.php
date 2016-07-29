<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogRuleStaging\Test\Constraint;

use Magento\CatalogRule\Test\Constraint\AssertCatalogPriceRuleInGrid;

/**
 * Class AssertCatalogPriceRuleStagingInGrid
 */
class AssertCatalogPriceRuleStagingInGrid extends AssertCatalogPriceRuleInGrid
{
    /**
     * Fields used to filter rows in the grid.
     * @var array
     */
    protected $fieldsToFilter = ['name'];
}
