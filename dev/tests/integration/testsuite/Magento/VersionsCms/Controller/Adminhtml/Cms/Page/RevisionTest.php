<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\VersionsCms\Controller\Adminhtml\Cms\Page;

/**
 * @magentoAppArea adminhtml
 */
class RevisionTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * @magentoDataFixture Magento/Cms/_files/pages.php
     */
    public function testPreviewAction()
    {
        /** @var $page \Magento\Cms\Model\Page */
        $page = $this->_objectManager->create('Magento\Cms\Model\Page');
        $page->load('page100', 'identifier');
        // fixture cms/page
        $this->getRequest()->setPostValue('page_id', $page->getId());
        $this->dispatch('backend/admin/cms_page_revision/preview/');
        $body = $this->getResponse()->getBody();
        $this->assertContains('<input id="preview_selected_revision"', $body);
        $this->assertNotContains('<select name="revision_switcher" id="revision_switcher">', $body);
    }
}
