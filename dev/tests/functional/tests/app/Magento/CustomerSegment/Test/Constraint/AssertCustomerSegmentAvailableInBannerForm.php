<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Constraint;

use Magento\Banner\Test\Page\Adminhtml\BannerNew;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCustomerSegmentAvailableInBannerForm
 * Assert that created customer segment is available in Banner edit page
 */
class AssertCustomerSegmentAvailableInBannerForm extends AbstractConstraint
{
    /**
     * Assert that created customer segment is available in Banner edit page
     *
     * @param BannerNew $bannerNew
     * @param CustomerSegment $customerSegment
     * @return void
     */
    public function processAssert(
        BannerNew $bannerNew,
        CustomerSegment $customerSegment
    ) {
        $bannerNew->open();
        \PHPUnit_Framework_Assert::assertTrue(
            $bannerNew->getSegmentBannerForm()->isCustomerSegmentVisible($customerSegment->getName()),
            'Customer segment is not available in Banner edit page.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer segment is available on Banner edit page.';
    }
}
