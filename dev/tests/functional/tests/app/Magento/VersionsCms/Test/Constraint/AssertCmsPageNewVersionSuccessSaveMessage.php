<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\VersionsCms\Test\Page\Adminhtml\CmsVersionEdit;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that after new CMS page version save successful message appears
 */
class AssertCmsPageNewVersionSuccessSaveMessage extends AbstractConstraint
{
    /**
     * New version success save message
     */
    const SUCCESS_SAVE_MESSAGE = 'You have created the new version.';

    /**
     * Assert that after new CMS page version save successful message appears
     *
     * @param CmsVersionEdit $cmsVersionEdit
     * @return void
     */
    public function processAssert(CmsVersionEdit $cmsVersionEdit)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_SAVE_MESSAGE,
            $cmsVersionEdit->getMessagesBlock()->getSuccessMessage()
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return '"You have created the new version" success save message is present.';
    }
}
