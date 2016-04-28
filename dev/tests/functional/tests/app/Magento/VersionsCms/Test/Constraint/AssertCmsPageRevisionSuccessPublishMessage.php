<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\Cms\Test\Page\AdminHtml\CmsPageIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert success publish message is displayed on the page.
 */
class AssertCmsPageRevisionSuccessPublishMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'high';
    /* end tags */

    /**
     * Text value to be checked.
     */
    const SUCCESS_PUBLISH_MESSAGE = 'You have published the revision.';

    /**
     * Assert success publish message is displayed on the page.
     *
     * @param CmsPageIndex $cmsPageIndex
     * @return void
     */
    public function processAssert(CmsPageIndex $cmsPageIndex)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_PUBLISH_MESSAGE,
            $cmsPageIndex->getMessagesBlock()->getSuccessMessage(),
            'Wrong success message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'CMS Page Revision success publish message is present.';
    }
}
