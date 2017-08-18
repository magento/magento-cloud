<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Staging\Test\Constraint;

use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that update campaign can not be created error message is correct.
 */
class AssertUpdateScheduledChangesErrorMessage extends AbstractConstraint
{
    /**
     * Assert that update campaign can not be created error message is correct.
     *
     * @param CatalogProductEdit $catalogProductEdit
     * @param string $errorMessage
     * @return void
     */
    public function processAssert(
        CatalogProductEdit $catalogProductEdit,
        $errorMessage
    ) {
        \PHPUnit_Framework_Assert::assertEquals(
            $errorMessage,
            $catalogProductEdit->getProductScheduleForm()->getErrorMessage(),
            'Update campaign can not be created error message is incorrect.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Update campaign can not be created error message is correct.';
    }
}
