<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\VersionsCms\Controller\Cms;

class PageTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * Test \Magento\VersionsCms\Block\Cms\Page::_addBreadcrumbs
     */
    public function testAddBreadcrumbs()
    {
        $this->dispatch('/enable-cookies/');
        $layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\LayoutInterface'
        );
        $breadcrumbsBlock = $layout->getBlock('breadcrumbs');
        $this->assertContains($breadcrumbsBlock->toHtml(), $this->getResponse()->getBody());
    }
}

