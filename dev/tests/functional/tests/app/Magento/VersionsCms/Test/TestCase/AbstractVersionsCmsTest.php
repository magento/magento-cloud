<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\TestCase;

use Magento\Cms\Test\Page\Adminhtml\CmsPageNew;
use Magento\Cms\Test\Page\Adminhtml\CmsPageIndex;
use Magento\VersionsCms\Test\Page\Adminhtml\CmsVersionEdit;
use Magento\VersionsCms\Test\Page\Adminhtml\CmsRevisionEdit;
use Magento\Mtf\TestCase\Injectable;

/**
 * Parent class for VersionsCms test, responsible for page injection.
 */
abstract class AbstractVersionsCmsTest extends Injectable
{
    /**
     * Cms index page.
     *
     * @var CmsPageIndex
     */
    protected $cmsPageIndex;

    /**
     * CmsPageNew page.
     *
     * @var CmsPageNew
     */
    protected $cmsPageNew;

    /**
     * CmsVersionEdit page.
     *
     * @var CmsVersionEdit
     */
    protected $cmsVersionEdit;

    /**
     * CmsRevisionEdit page.
     *
     * @var CmsRevisionEdit
     */
    protected $cmsRevisionEdit;

    /**
     * Injection data.
     *
     * @param CmsPageIndex $cmsPageIndex
     * @param CmsPageNew $cmsPageNew
     * @param CmsVersionEdit $cmsVersionEdit
     * @param CmsRevisionEdit $cmsRevisionEdit
     * @return void
     */
    public function __inject(
        CmsPageIndex $cmsPageIndex,
        CmsPageNew $cmsPageNew,
        CmsVersionEdit $cmsVersionEdit,
        CmsRevisionEdit $cmsRevisionEdit
    ) {
        $this->cmsPageIndex = $cmsPageIndex;
        $this->cmsPageNew = $cmsPageNew;
        $this->cmsVersionEdit = $cmsVersionEdit;
        $this->cmsRevisionEdit = $cmsRevisionEdit;
    }
}
