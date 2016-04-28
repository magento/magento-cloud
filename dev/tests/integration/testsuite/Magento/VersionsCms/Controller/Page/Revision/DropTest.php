<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\VersionsCms\Controller\Page\Revision;

use Magento\Customer\Model\Context;

class DropTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoDataFixture Magento/Cms/_files/pages.php
     */
    public function testDropAction()
    {
        $storeId = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Store\Model\StoreManagerInterface'
        )->getDefaultStoreView();
        // fixture design_change
        $context = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->get('Magento\Framework\App\Http\Context');
        $context->setValue(Context::CONTEXT_AUTH, false, false);

        $this->getRequest()->setParam('preview_selected_store', $storeId);

        /** @var $page \Magento\Cms\Model\Page */
        $page = $this->_objectManager->create('Magento\Cms\Model\Page');
        $page->load('page100', 'identifier');
        // fixture cms/page
        $this->getRequest()->setPostValue('page_id', $page->getId());

        $this->dispatch(\Magento\VersionsCms\Model\Page\Revision::PREVIEW_URI);
        $this->assertContains('static/frontend/Magento/luma', $this->getResponse()->getBody());
        $this->assertContains($page->getContent(), $this->getResponse()->getBody());
    }
}