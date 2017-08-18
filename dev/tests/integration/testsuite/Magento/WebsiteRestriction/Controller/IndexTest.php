<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\WebsiteRestriction\Controller;

class IndexTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoConfigFixture current_store general/restriction/is_active 1
     * @magentoConfigFixture current_store general/restriction/mode 0
     * @magentoConfigFixture current_store general/restriction/cms_page page_design_blank
     * @magentoConfigFixture current_store general/restriction/http_status 1
     * @magentoDataFixture Magento/Cms/_files/pages.php
     */
    public function testStubAction()
    {
        $page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Cms\Model\Page::class);
        $page->load('page100', 'identifier');
        // fixture

        $websiteId = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Store\Model\StoreManagerInterface::class
        )->getWebsite(
            'base'
        )->getId();
        // fixture, pre-installed
        /**
         * besides more expensive, cleaning by tags currently triggers system setup = DDL = breaks transaction
         * therefore cleanup is performed by cache ID
         */
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\App\CacheInterface::class
        )->remove(
            "RESTRICTION_LANGING_PAGE_{$websiteId}"
        );
        $this->markTestIncomplete('MAGETWO-4342');

        $this->dispatch('restriction/index/stub');
        $body = $this->getResponse()->getBody();
        $this->assertContains('<h1>Cms Page Design Blank Title</h1>', $body);
        $this->assertContains('theme/frontend/default/blank/en_US/Magento_Theme/favicon.ico', $body);
        $this->assertHeaderPcre('Http/1.1', '/^503 Service Unavailable$/');
    }
}
