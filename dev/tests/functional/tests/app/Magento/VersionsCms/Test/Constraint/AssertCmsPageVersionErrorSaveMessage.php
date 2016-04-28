<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\VersionsCms\Test\Page\Adminhtml\CmsVersionEdit;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that after change access level of last public version to private error message appears
 */
class AssertCmsPageVersionErrorSaveMessage extends AbstractConstraint
{
    /**
     * Save error message
     */
    const ERROR_SAVE_MESSAGE = 'Cannot change version access level because it is the last public version for its page.';

    /**
     * Assert that after change access level of last public version to private error message appears
     *
     * @param CmsVersionEdit $cmsVersionEdit
     * @return void
     */
    public function processAssert(CmsVersionEdit $cmsVersionEdit)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::ERROR_SAVE_MESSAGE,
            $cmsVersionEdit->getMessagesBlock()->getErrorMessage()
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return '"' . self::ERROR_SAVE_MESSAGE . '" error message is present.';
    }
}
