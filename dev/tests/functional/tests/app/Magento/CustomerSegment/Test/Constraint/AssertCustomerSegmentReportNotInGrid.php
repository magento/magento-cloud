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
 * Class AssertCustomerSegmentReportNotInGrid
 * Assert that created customer is absent in a customer segment grid
 */
class AssertCustomerSegmentReportNotInGrid extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'high';
    /* end tags */

    /**
     * Assert that created customer is absent in a customer segment grid
     *
     * @param array $notFoundCustomers
     * @param array $customers
     * @param CustomerSegmentReportDetail $reportDetailPage
     * @return void
     */
    public function processAssert(
        array $notFoundCustomers,
        array $customers,
        CustomerSegmentReportDetail $reportDetailPage
    ) {
        $errors = [];
        foreach ($notFoundCustomers as $index) {
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

            if ($reportDetailPage->getDetailGrid()->isRowVisible($filter)) {
                $errors[] = '- row "' . implode(', ', $filter) . '" was found in the grid report';
            }
        }

        \PHPUnit_Framework_Assert::assertEmpty(
            $errors,
            'When checking the report grid, the following errors were found:' . PHP_EOL . implode(PHP_EOL, $errors)
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Report grid does not contain customers which must be absent.';
    }
}
