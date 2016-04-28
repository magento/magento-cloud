<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\VersionsCms\Controller\Adminhtml\Cms;

/**
 * @magentoAppArea adminhtml
 */
class PageTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * Checks if \Magento\VersionsCms\Block\Adminhtml\Cms\Page::_prepareLayout finds child 'grid' block
     */
    public function testIndexAction()
    {
        $this->dispatch('backend/admin/cms_page/index');
        $content = $this->getResponse()->getBody();
        $this->assertContains('Version Control', $content);
    }
}
