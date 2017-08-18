<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogPermissions\Test\TestCase;

use Magento\Mtf\TestStep\TestStepFactory;
use Magento\Mtf\TestCase\Injectable;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Util\Command\Cli\Indexer;
use Magento\Mtf\Util\Command\Cli\Cache;
use Magento\Catalog\Test\Page\Adminhtml\CatalogCategoryEdit;
use Magento\Catalog\Test\Fixture\Category;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Cms\Test\Page\CmsIndex;

/**
 * Preconditions:
 * 1. Setup category permissions in configuration.
 * 2. Clear cache.
 * 3. Perform reindex for the new permissions indexers.
 * 4. Create new customers assigned to different groups: General, Wholesale, Retailer.
 * 5. Create 2 new subcategories of the root Default Category.
 *
 * Steps:
 * 1. Open second category in the admin panel.
 * 2. Set category permissions for that category.
 * 3. Login as a customer.
 * 4. Perform assertions.
 *
 * @ZephyrId MAGETWO-35639
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CategoryPermissionsTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Category edit page.
     *
     * @var CatalogCategoryEdit
     */
    protected $categoryEditPage;

    /**
     * Test step factory.
     *
     * @var TestStepFactory
     */
    protected $testStepFactory;

    /**
     * Configuration settings.
     *
     * @var TestStepFactory
     */
    protected $configData;

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Indexer.
     *
     * @var Indexer
     */
    protected $indexer;

    /**
     * Cache.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Prepare test data.
     *
     * @param TestStepFactory $testStepFactory
     * @param FixtureFactory $fixtureFactory
     * @param CatalogCategoryEdit $categoryEditPage
     * @param Indexer $indexer
     * @param Cache $cache
     * @return void
     */
    public function __prepare(
        TestStepFactory $testStepFactory,
        FixtureFactory $fixtureFactory,
        CatalogCategoryEdit $categoryEditPage,
        Indexer $indexer,
        Cache $cache
    ) {
        $this->testStepFactory = $testStepFactory;
        $this->fixtureFactory = $fixtureFactory;
        $this->categoryEditPage = $categoryEditPage;
        $this->indexer = $indexer;
        $this->cache = $cache;
    }

    /**
     * Test category permissions for different customer groups.
     *
     * @param CatalogProductSimple $product
     * @param Category $permissionsSettings
     * @param CmsIndex $cmsIndex
     * @param array $categoryDatasets
     * @param string $configData
     * @param Customer|null $customer [optional]
     * @return array
     */
    public function test(
        CatalogProductSimple $product,
        Category $permissionsSettings,
        CmsIndex $cmsIndex,
        array $categoryDatasets,
        $configData,
        Customer $customer = null
    ) {
        // Preconditions
        $this->configData = $configData;
        $this->testStepFactory->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            [
                'configData' => $configData,
                'flushCache' => true
            ]
        )->run();
        $this->indexer->reindex(['catalogpermissions_category', 'catalogpermissions_product']);
        $categories = [];
        foreach ($categoryDatasets as $key => $categoryDataset) {
            $categories[$key] = $this->fixtureFactory->createByCode(
                'category',
                [
                    'dataset' => $categoryDataset['dataset'],
                    'data' => [
                        'category_products' => [
                            'dataset' => $categoryDataset['product'],
                            'products' => [$product],
                        ]
                    ]
                ]
            );
            $categories[$key]->persist();
        }

        // Test steps
        $this->categoryEditPage->open(['id' => $categories[1]->getId()]);
        $this->categoryEditPage->getEditForm()->fill($permissionsSettings);
        $this->categoryEditPage->getFormPageActions()->save();
        // Save index page to cache.
        $cmsIndex->open();
        if ($customer) {
            $customer->persist();
            $this->testStepFactory->create(
                \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
                ['customer' => $customer]
            )->run();
        }

        return [
            'categories' => $categories
        ];
    }

    /**
     * Logout customer and set default configuration.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->testStepFactory->create(
            \Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep::class
        )->run();
        $this->testStepFactory->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $this->configData, 'rollback' => true]
        )->run();
        $this->cache->flush();
    }
}
