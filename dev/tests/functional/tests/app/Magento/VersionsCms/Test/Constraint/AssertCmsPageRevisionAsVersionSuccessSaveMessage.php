<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\VersionsCms\Test\Page\Adminhtml\CmsVersionEdit;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\ObjectManager;
use Magento\Mtf\System\Event\EventManagerInterface;

/**
 * Assert that after save CMS page revision as version save successful message appears.
 */
class AssertCmsPageRevisionAsVersionSuccessSaveMessage extends AssertCmsPageNewVersionSuccessSaveMessage
{
    /**
     * Browser object.
     *
     * @var BrowserInterface
     */
    protected $browser;

    /**
     * @constructor
     * @param ObjectManager $objectManager
     * @param EventManagerInterface $eventManager
     * @param BrowserInterface $browser
     */
    public function __construct(
        ObjectManager $objectManager,
        EventManagerInterface $eventManager,
        BrowserInterface $browser
    ) {
        parent::__construct($objectManager, $eventManager);
        $this->browser = $browser;
    }

    /**
     * Assert that after save CMS page revision as version save successful message appears.
     *
     * @param CmsVersionEdit $cmsVersionEdit
     * @return void
     */
    public function processAssert(CmsVersionEdit $cmsVersionEdit)
    {
        $this->browser->selectWindow();
        parent::processAssert($cmsVersionEdit);
        $this->browser->closeWindow();
        $this->browser->selectWindow();
    }
}
