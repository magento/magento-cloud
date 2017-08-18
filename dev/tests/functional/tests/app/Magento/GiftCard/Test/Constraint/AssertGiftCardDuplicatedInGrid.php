<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Constraint;

use Magento\Catalog\Test\Page\Adminhtml\CatalogProductIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Class AssertGiftCardDuplicatedInGrid
 */
class AssertGiftCardDuplicatedInGrid extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that duplicated product is found by sku and has correct product type, attribute set,
     * product status disabled and out of stock
     *
     * @param FixtureInterface $product
     * @param CatalogProductIndex $productGrid
     * @return void
     */
    public function processAssert(FixtureInterface $product, CatalogProductIndex $productGrid)
    {
        $filter = [
            'name' => $product->getName(),
            'visibility' => $product->getVisibility(),
            'status' => 'Disabled',
            'sku' => $product->getSku() . '-1',
            'type' => 'Gift Card',
        ];

        $productGrid->open()->getProductGrid()->search($filter);

        \PHPUnit_Framework_Assert::assertTrue(
            $productGrid->getProductGrid()->isRowVisible($filter, false),
            'Duplicated gift card is absent in Products grid.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'The gift card has been successfully found, according to the filters.';
    }
}
