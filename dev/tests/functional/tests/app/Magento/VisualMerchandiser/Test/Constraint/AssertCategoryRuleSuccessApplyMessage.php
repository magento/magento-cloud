<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Constraint;

use Magento\Catalog\Test\Page\Adminhtml\CatalogCategoryEdit;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that success message is displayed after category save.
 */
class AssertCategoryRuleSuccessApplyMessage extends AbstractConstraint
{
    /**
     * Success category rules applied message.
     */
    const SUCCESS_MESSAGE = 'Category rules applied';

    /**
     * Assert that success message is displayed after category save.
     *
     * @param CatalogCategoryEdit $catalogCategoryEdit
     * @return void
     */
    public function processAssert(CatalogCategoryEdit $catalogCategoryEdit)
    {
        $actualMessage = $catalogCategoryEdit->getMessagesBlock()->getSuccessMessage();
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_MESSAGE,
            $actualMessage,
            'Wrong success message is displayed.'
            . "\nExpected: " . self::SUCCESS_MESSAGE
            . "\nActual: " . $actualMessage
        );
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'Success message is displayed.';
    }
}
