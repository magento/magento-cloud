<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that product update delete warning message is correct.
 */
class AssertProductUpdateDeleteWarningMessage extends AbstractConstraint
{
    /**
     * Expected warning message.
     *
     * @var string
     */
    const EXPECTED_MESSAGE = 'The product will be removed from the update and all scheduled changes will be lost.';

    /**
     * Assert that product update delete warning message is correct.
     *
     * @param CatalogProductEdit $catalogProductEdit
     * @return void
     */
    public function processAssert(
        CatalogProductEdit $catalogProductEdit
    ) {
        \PHPUnit_Framework_Assert::assertEquals(
            self::EXPECTED_MESSAGE,
            $catalogProductEdit->getUpdateDeleteBlock()->getWarningMessage(),
            'Product update deleted message is not correct.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Product update deleted message is correct.';
    }
}
