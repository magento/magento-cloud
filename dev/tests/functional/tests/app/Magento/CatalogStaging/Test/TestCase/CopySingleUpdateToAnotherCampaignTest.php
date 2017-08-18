<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\TestCase;

use Magento\Mtf\TestCase\Injectable;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepFactory;

/**
 * Preconditions:
 * 1. Create 3 simple products.
 *
 * Steps:
 * 1. Schedule product update campaigns for products.
 * 2. Verify that all product update campaigns are visible in staging grid.
 * 3. Verify that correct products are assigned to each product update campaign.
 * 4. Open each product preview in the StoreFront and verify that update prices are correct.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-55146
 */
class CopySingleUpdateToAnotherCampaignTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Catalog product index page.
     *
     * @var CatalogProductEdit
     */
    private $catalogProductEdit;

    /**
     * Fixture factory instance.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Factory for Test Steps.
     *
     * @var TestStepFactory
     */
    private $testStepFactory;

    /**
     * Perform needed injections.
     *
     * @param CatalogProductEdit $catalogProductEdit
     * @param FixtureFactory $fixtureFactory
     * @param TestStepFactory $testStepFactory
     * @return void
     */
    public function __inject(
        CatalogProductEdit $catalogProductEdit,
        FixtureFactory $fixtureFactory,
        TestStepFactory $testStepFactory
    ) {
        $this->catalogProductEdit = $catalogProductEdit;
        $this->fixtureFactory = $fixtureFactory;
        $this->testStepFactory = $testStepFactory;
    }

    /**
     * Test copy single update to another campaign.
     *
     * @param array $productsList
     * @param array $productUpdate
     * @param array $staging
     * @return array
     */
    public function test(
        array $productsList,
        array $productUpdate,
        array $staging
    ) {
        // Preconditions
        $products = $this->createProducts($productsList);
        $prices = [];
        $updates = [];

        // Test steps
        for ($i = 0; $i < count($products); $i++) {
            $this->catalogProductEdit->open(['id' => $products[$i]->getId()]);
            for ($j = 1; $j <= count($staging); $j++) {
                if (!isset($staging[$i][$j])) {
                    continue;
                }
                $this->catalogProductEdit->getProductScheduleBlock()->clickScheduleNewUpdate();
                if (!isset($staging[$i][$j]['use_existing'])) {
                    $updates[$j] = $this->fixtureFactory->createByCode(
                        'update',
                        [
                            'dataset' => $staging[$i][$j]['dataset'],
                        ]
                    );
                    $this->catalogProductEdit->getProductScheduleForm()->fill($updates[$j]);
                } else {
                    $this->catalogProductEdit
                        ->getStagingGrid()
                        ->assignToExistingCampaign($updates[$j]->getName());
                }

                if (isset($productUpdate[$i][$j])) {
                    $simpleProduct = $this->fixtureFactory->createByCode(
                        'catalogProductSimple',
                        $productUpdate[$i][$j]
                    );
                    $this->catalogProductEdit->getStagingProductForm()->fill($simpleProduct);
                    $prices[$i][$j] = $simpleProduct->getPrice();
                }
                $this->catalogProductEdit->getStagingFormPageActions()->save();
            }
        }

        return [
            'prices' => $prices,
            'products' => $products,
            'updates' => $updates
        ];
    }

    /**
     * Create products.
     *
     * @param array $products
     * @return array
     */
    private function createProducts(array $products)
    {
        $createProductsStep = $this->testStepFactory->create(
            \Magento\Catalog\Test\TestStep\CreateProductsStep::class,
            ['products' => $products]
        );

        return $createProductsStep->run()['products'];
    }
}
