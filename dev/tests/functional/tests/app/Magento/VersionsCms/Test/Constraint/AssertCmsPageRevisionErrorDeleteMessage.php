<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\VersionsCms\Test\Page\Adminhtml\CmsVersionEdit;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that error delete message is displayed on the page
 */
class AssertCmsPageRevisionErrorDeleteMessage extends AbstractConstraint
{
    /**
     * Text value to be checked
     */
    const ERROR_DELETE_MESSAGE = 'Revision #%d could not be removed because it is published.';

    /**
     * Assert that error delete message is displayed on the page
     *
     * @param CmsVersionEdit $cmsVersionEdit
     * @param array $results
     * @return void
     */
    public function processAssert(CmsVersionEdit $cmsVersionEdit, array $results)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::ERROR_DELETE_MESSAGE, $results['quantity']),
            $cmsVersionEdit->getMessagesBlock()->getErrorMessage(),
            'Wrong error message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'CMS Page Revision error delete message is present.';
    }
}
