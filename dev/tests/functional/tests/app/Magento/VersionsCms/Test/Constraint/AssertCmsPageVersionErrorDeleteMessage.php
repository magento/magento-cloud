<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\Cms\Test\Page\Adminhtml\CmsPageNew;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that error delete message is displayed on the page
 */
class AssertCmsPageVersionErrorDeleteMessage extends AbstractConstraint
{
    /**
     * Text value to be checked
     */
    const ERROR_DELETE_MESSAGE = 'Version "%s" cannot be removed because its revision is published.';

    /**
     * Assert that error delete message is displayed on the page
     *
     * @param CmsPageNew $cmsPageNew
     * @param array $results
     * @return void
     */
    public function processAssert(CmsPageNew $cmsPageNew, array $results)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::ERROR_DELETE_MESSAGE, $results['label']),
            $cmsPageNew->getMessagesBlock()->getErrorMessage(),
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
        return 'CMS Page Version success delete message is present.';
    }
}
