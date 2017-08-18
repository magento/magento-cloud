<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCustomerSegmentMatchedCustomersInGrid
 * Check that the customer according to search criteria presents in the grid and have correct values for
 * the following columns
 */
class AssertCustomerSegmentMatchedCustomersInGrid extends AbstractConstraint
{
    /**
     * Assert that the customer according to search criteria presents in the grid and have correct values for
     * the following columns:
     * "Name";
     * "Email";
     * "Group";
     * "Phone";
     * "ZIP";
     * "Country";
     * "State/Province";
     *
     * @param CustomerSegment $customerSegment
     * @param Customer $customer
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     * @return void
     */
    public function processAssert(
        CustomerSegment $customerSegment,
        Customer $customer,
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerSegmentNew $customerSegmentNew
    ) {
        $customerSegmentIndex->open();
        $website = $customerSegment->getWebsiteIds();
        $filter = [
            'grid_segment_name' => $customerSegment->getName(),
            'grid_segment_is_active' => $customerSegment->getIsActive(),
            'grid_segment_website' => reset($website),
        ];
        $customerSegmentIndex->getGrid()->searchAndOpen($filter);
        $customerSegmentNew->getPageMainActions()->refreshSegmentData();
        $customerSegmentNew->getCustomerSegmentForm()->openTab('matched_customers');

        $customerFilter = [
            'name' => $customer->getFirstname() . ' ' . $customer->getLastname(),
            'email' => $customer->getEmail(),
            'group_id' => $customer->getGroupId(),
        ];

        if (!empty($customer->getAddress()[0])) {
            $address = $customer->getAddress()[0];
            $customerFilter['telephone'] = $address['telephone'];
            $customerFilter['postcode'] = $address['postcode'];
            $customerFilter['country_id'] = $address['country_id'];
            $customerFilter['region_id'] = $address['region_id'];
        }

        \PHPUnit_Framework_Assert::assertTrue(
            $customerSegmentNew->getCustomerSegmentGrid()->isRowVisible($customerFilter),
            'Customer with '
            . 'name \'' . $customerFilter['name'] . '\', '
            . 'email \'' . $customerFilter['email'] . '\' '
            . 'is absent in Customer grid on the Customer Segment page.'
        );
    }

    /**
     * Text success exist Customer in grid
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer is present in Customer grid on the Customer Segment page.';
    }
}
