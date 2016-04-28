<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\Cms\Test\Page\Adminhtml\CmsPageNew;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that success delete message is displayed on the page
 */
class AssertCmsPageVersionSuccessDeleteMessage extends AbstractConstraint
{
    /**
     * Text value to be checked
     */
    const SUCCESS_DELETE_MESSAGE = 'You have deleted the version.';

    /**
     * Assert that success delete message is displayed on the page
     *
     * @param CmsPageNew $cmsPageNew
     * @return void
     */
    public function processAssert(CmsPageNew $cmsPageNew)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_DELETE_MESSAGE,
            $cmsPageNew->getMessagesBlock()->getSuccessMessage(),
            'Wrong success message is displayed.'
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
