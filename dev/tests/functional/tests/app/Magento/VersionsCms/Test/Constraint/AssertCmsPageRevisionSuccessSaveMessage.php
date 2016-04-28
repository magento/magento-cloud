<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\VersionsCms\Test\Page\Adminhtml\CmsVersionEdit;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that success save message is displayed on the CmsVersionEdit page
 */
class AssertCmsPageRevisionSuccessSaveMessage extends AbstractConstraint
{
    /**
     * Text value to be checked
     */
    const SUCCESS_SAVE_MESSAGE = 'You have saved the revision.';

    /**
     * Assert that success save message is displayed on the CmsVersionEdit page
     *
     * @param CmsVersionEdit $cmsVersionEdit
     * @return void
     */
    public function processAssert(CmsVersionEdit $cmsVersionEdit)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_SAVE_MESSAGE,
            $cmsVersionEdit->getMessagesBlock()->getSuccessMessage(),
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
        return 'Success save message is present on CmsVersionEdit page.';
    }
}
