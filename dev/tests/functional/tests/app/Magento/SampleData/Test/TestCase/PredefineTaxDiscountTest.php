<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleData\Test\TestCase;

use Magento\CatalogRule\Test\Fixture\CatalogRule;
use Magento\Config\Test\Fixture\ConfigData;
use Magento\SalesRule\Test\Fixture\SalesRule;
use Magento\Tax\Test\Fixture\TaxRule;
use Magento\Mtf\TestCase\Injectable;

/**
 * Predefine tax and discount data.
 *
 * @ticketId MTA-404
 */
class PredefineTaxDiscountTest extends Injectable
{
    /**
     * Predefine tax and discount data.
     *
     * @param TaxRule $tax
     * @param SalesRule $cartPriceRule
     * @param CatalogRule $catalogRule
     * @param ConfigData $store
     * @return void
     */
    public function test(TaxRule $tax, SalesRule $cartPriceRule, CatalogRule $catalogRule, ConfigData $store)
    {
        $cartPriceRule->persist();
        $tax->persist();
        $catalogRule->persist();
        $store->persist();
    }
}
