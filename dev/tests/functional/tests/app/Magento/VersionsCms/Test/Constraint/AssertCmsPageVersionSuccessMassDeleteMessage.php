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
class AssertCmsPageVersionSuccessMassDeleteMessage extends AbstractConstraint
{
    /**
     * Text value to be checked
     */
    const SUCCESS_DELETE_MESSAGE = 'A total of %d record(s) have been deleted.';

    /**
     * Assert that success delete message is displayed on the page
     *
     * @param CmsPageNew $cmsPageNew
     * @param array $results
     * @return void
     */
    public function processAssert(CmsPageNew $cmsPageNew, array $results)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::SUCCESS_DELETE_MESSAGE, $results['quantity']),
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
