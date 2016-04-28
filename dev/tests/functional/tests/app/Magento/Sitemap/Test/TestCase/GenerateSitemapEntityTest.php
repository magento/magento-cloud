<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sitemap\Test\TestCase;

use Magento\Catalog\Test\Fixture\Category;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Cms\Test\Fixture\CmsPage;
use Magento\Sitemap\Test\Fixture\Sitemap;
use Magento\Sitemap\Test\Page\Adminhtml\SitemapIndex;
use Magento\Sitemap\Test\Page\Adminhtml\SitemapNew;
use Magento\Mtf\TestCase\Injectable;

/**
 * Cover generating Sitemap Entity
 *
 * Test Flow:
 * Preconditions:
 *  1. Create category
 *  2. Create simple product
 *  3. Create CMS page
 * Steps:
 *  1. Log in as admin user from data set.
 *  2. Navigate to Marketing > SEO and Search > Site Map.
 *  3. Click "Add Sitemap" button.
 *  4. Fill out all data according to data set.
 *  5. Click "Save" button.
 *  6. Perform all assertions.
 *
 * @group XML_Sitemap_(PS)
 * @ZephyrId MAGETWO-25124
 */
class GenerateSitemapEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'PS';
    /* end tags */

    /**
     * Sitemap grid page
     *
     * @var SitemapIndex
     */
    protected $sitemapIndex;

    /**
     * Sitemap new page
     *
     * @var SitemapNew
     */
    protected $sitemapNew;

    /**
     * Inject data
     *
     * @param SitemapIndex $sitemapIndex
     * @param SitemapNew $sitemapNew
     * @return void
     */
    public function __inject(
        SitemapIndex $sitemapIndex,
        SitemapNew $sitemapNew
    ) {
        $this->sitemapIndex = $sitemapIndex;
        $this->sitemapNew = $sitemapNew;
    }

    /**
     * Generate Sitemap Entity
     *
     * @param Sitemap $sitemap
     * @param CatalogProductSimple $product
     * @param Category $catalog
     * @param CmsPage $cmsPage
     * @return void
     */
    public function testGenerateSitemap(
        Sitemap $sitemap,
        CatalogProductSimple $product,
        Category $catalog,
        CmsPage $cmsPage
    ) {
        // Preconditions
        $product->persist();
        $catalog->persist();
        $cmsPage->persist();

        // Steps
        $this->sitemapIndex->open();
        $this->sitemapIndex->getGridPageActions()->addNew();
        $this->sitemapNew->getSitemapForm()->fill($sitemap);
        $this->sitemapNew->getSitemapPageActions()->saveAndGenerate();
    }
}
