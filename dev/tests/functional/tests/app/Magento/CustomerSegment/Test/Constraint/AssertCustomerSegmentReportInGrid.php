<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Constraint;

use Magento\Customer\Test\Fixture\Address;
use Magento\Customer\Test\Fixture\Customer;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentReportDetail;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCustomerSegmentReportInGrid
 * Assert that created customer is present in customer segment report grid
 */
class AssertCustomerSegmentReportInGrid extends AbstractConstraint
{
    /**
     * Assert that created customer segment report presents in the grid and customer from it has correct values
     * for the following columns:
     * - name
     * - email
     * - group
     * - phone
     * - ZIP
     * - country
     * - state/province
     *
     * @param array $foundCustomers
     * @param array $customers
     * @param CustomerSegmentReportDetail $reportDetailPage
     * @return void
     */
    public function processAssert(
        array $foundCustomers,
        array $customers,
        CustomerSegmentReportDetail $reportDetailPage
    ) {
        $errors = [];
        foreach ($foundCustomers as $index) {
            /** @var Customer $customer */
            $customer = $customers[$index];
            /** @var Address $address */
            $address = $customer->getDataFieldConfig('address')['source']->getAddresses()[0];
            $filter = [
                'grid_name' => $address->getFirstname() . ' ' . $address->getLastname(),
                'grid_email' => $customer->getEmail(),
                'grid_group' => $customer->getGroupId(),
                'grid_telephone' => $address->getTelephone(),
                'grid_billing_postcode' => $address->getPostcode(),
                'grid_billing_country_id' => $address->getCountryId(),
                'grid_billing_region' => $address->getRegionId(),
            ];

            if (!$reportDetailPage->getDetailGrid()->isRowVisible($filter)) {
                $errors[] = '- row "' . implode(', ', $filter) . '" was not found in the grid report';
            }
        }

        \PHPUnit_Framework_Assert::assertEmpty(
            $errors,
            'When checking the grid, the following errors were found:' . PHP_EOL . implode(PHP_EOL, $errors)
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'All required customers are present in customer segment report grid.';
    }
}
