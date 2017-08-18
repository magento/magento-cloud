<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GoogleTagManager\Test\TestCase;

use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductIndex;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Configure GTM functionality
 *
 * Steps:
 * 1. Create category.
 * 2. Create product with created category.
 * 3. Perform all assertions.
 *
 * @group Google_Tag_Manager
 * @ZephyrId MAGETWO-39521, MAGETWO-39522
 */
class GtmProductPageTest extends Injectable
{
    /* tags */
    /* end tags */

    /**
     * Catalog product view page
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Catalog product index page in backend.
     *
     * @var CatalogProductIndex
     */
    protected $catalogProductIndex;

    /**
     * Catalog product edit page in backend.
     *
     * @var CatalogProductEdit
     */
    protected $catalogProductEdit;

    /**
     * Fixture Factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Configuration setting.
     *
     * @var string
     */
    protected $configData;

    /**
     * Products data
     *
     * @var array
     */
    protected $products = [];

    /**
     * Prepare test data
     *
     * @param CatalogProductView $catalogProductView
     * @param CatalogProductIndex $catalogProductIndex
     * @param CatalogProductEdit $catalogProductEdit
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __prepare(
        CatalogProductView $catalogProductView,
        CatalogProductIndex $catalogProductIndex,
        CatalogProductEdit $catalogProductEdit,
        FixtureFactory $fixtureFactory
    ) {
        $this->catalogProductView = $catalogProductView;
        $this->catalogProductIndex = $catalogProductIndex;
        $this->catalogProductEdit = $catalogProductEdit;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * Check GTM code on product page
     *
     * @param string $configData
     * @param array $productsData
     * @return array
     */
    public function test(
        $configData,
        $productsData
    ) {
        // Preconditions
        $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $configData]
        )->run();
        $this->prepareProducts($productsData);
        $this->assignPromotedProducts($this->products);
        return ['products' => $this->products];
    }

    /**
     * Create products
     *
     * @param string $productList
     * @return void
     */
    protected function prepareProducts($productList)
    {
        $list = array_map('trim', explode(',', $productList));
        foreach ($list as $item) {
            list($productName, $fixtureCode, $dataSet) = array_map('trim', explode('::', $item));
            $product = $this->fixtureFactory->createByCode($fixtureCode, ['dataset' => $dataSet]);
            $product->persist();
            $this->products[$productName] = $product;
        }
    }

    /**
     * Assign promoted products.
     *
     * @param string $products
     * @return void
     */
    protected function assignPromotedProducts($products)
    {
        $sourceProduct = $products['product'];
        $assignedProducts[] = $products['promoted_product'];
        $promotionData = $this->fixtureFactory->create(
            get_class($sourceProduct),
            [
                'data' => [
                    'up_sell_products' => ['products' => $assignedProducts],
                    'related_products' => ['products' => $assignedProducts]
                ]
            ]
        );
        $this->catalogProductIndex->open()->getProductGrid()->searchAndOpen(['sku' => $sourceProduct->getSku()]);
        $this->catalogProductEdit->getProductForm()->fill($promotionData);
        $this->catalogProductEdit->getFormPageActions()->save();
        $this->catalogProductEdit->getMessagesBlock()->waitSuccessMessage();
    }

    /**
     * Clean data after running test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $this->configData, 'rollback' => true]
        )->run();
    }
}
