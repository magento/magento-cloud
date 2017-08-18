<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\MultipleWishlist\Test\Page\Adminhtml\CustomerWishlistReport;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertWishlistReportGrid
 * Assert that added to the customer wish list products present in the grid and products have correct values
 */
class AssertWishlistReportGrid extends AbstractConstraint
{
    /**
     * Assert that added to the customer wish list products present in the grid and products have correct values
     *
     * @param CustomerWishlistReport $customerWishlistReport
     * @param MultipleWishlist $multipleWishlist
     * @param Customer $customer
     * @param array $products
     * @param array $wishlist
     * @return void
     */
    public function processAssert(
        CustomerWishlistReport $customerWishlistReport,
        MultipleWishlist $multipleWishlist,
        Customer $customer,
        array $products,
        array $wishlist
    ) {
        $customerWishlistReport->open();
        foreach ($products as $key => $product) {
            $filter = [
                'customer_name' => $customer->getFirstname() . ' ' . $customer->getLastname(),
                'wishlist_name' => $multipleWishlist->getName(),
                'visibility' => $multipleWishlist->getVisibility() === 'No' ? 'Private' : 'Public',
                'product_name' => $product->getName(),
                'product_sku' => $product->getSku(),
                'item_comment' => $wishlist[$key]['description'],
            ];
            $errorMessage = implode(', ', $filter);
            \PHPUnit_Framework_Assert::assertTrue(
                $customerWishlistReport->getWishlistReportGrid()->isRowVisible($filter, false, false),
                'Wish List with following data: \'' . $errorMessage . '\' '
                . 'is absent in Customer Wish List Report grid.'
            );
        }
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Wish List is present in Customer Wish List Report grid.';
    }
}
