<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\TestCase;

use Magento\Mtf\TestCase\Injectable;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Mtf\TestStep\TestStepFactory;

/**
 * Preconditions:
 * 1. Create simple product enabled in 2 stores.
 *
 * Steps:
 * 1. Create three update campaigns for Default Store.
 * 2. Create one update campaign for Custom Store.
 * 3. Click "Preview" button for each of the update campaigns.
 * 4. Perform assertions.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-49851, MAGETWO-55004
 */
class CreateCustomWebsiteProductUpdateTest extends Injectable
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
     * Configuration setting.
     *
     * @var string
     */
    private $configData;

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
     * Create product updates and validate them in product preview.
     *
     * @param CatalogProductSimple $product
     * @param CatalogProductSimple $productUpdateForCustomWebsite
     * @param array $productUpdate
     * @param array $staging
     * @param string|null $configData [optional]
     * @return array
     */
    public function test(
        CatalogProductSimple $product,
        CatalogProductSimple $productUpdateForCustomWebsite,
        array $productUpdate,
        array $staging,
        $configData = null
    ) {
        // Preconditions
        $this->configData = $configData;
        $this->testStepFactory->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $this->configData]
        )->run();
        $product->persist();
        $stores = $product->getDataFieldConfig('website_ids')['source']->getStores();
        $this->catalogProductEdit->open(['id' => $product->getId()]);

        // Test steps
        $updates = [];
        $prices = [];
        for ($i = 0; $i < count($staging); $i++) {
            $simpleProduct = $this->fixtureFactory->createByCode(
                'catalogProductSimple',
                $productUpdate[$i]
            );
            $prices[$i] = $simpleProduct->getPrice();
            $updates[$i] = $this->fixtureFactory->createByCode(
                'update',
                [
                    'dataset' => $staging[$i]['dataset'],
                ]
            );
            $this->catalogProductEdit->getProductScheduleBlock()->clickScheduleNewUpdate();
            if ($i == 0) {
                $this->catalogProductEdit->getStagingFormPageActions()
                    ->selectStoreView($stores[0]->getGroupId() . '/' . $stores[0]->getName());
                $this->catalogProductEdit->getModalBlock()->acceptAlert();
            }
            $this->catalogProductEdit->getProductScheduleForm()->fill($updates[$i]);
            $this->catalogProductEdit->getStagingProductForm()->fill($simpleProduct);
            $this->catalogProductEdit->getStagingFormPageActions()->save();
        }
        $this->catalogProductEdit->getProductScheduleBlock()->editUpdate($updates[2]->getName());
        $this->catalogProductEdit->getStagingFormPageActions()
            ->selectStoreView($stores[1]->getGroupId() . '/' . $stores[1]->getName());
        $this->catalogProductEdit->getModalBlock()->acceptAlert();
        $this->catalogProductEdit->getStagingProductForm()->fill($productUpdateForCustomWebsite);
        $this->catalogProductEdit->getStagingFormPageActions()->save();

        return [
            'product' => $product,
            'updates' => $updates,
            'prices' => $prices,
            'anotherWebsitePreview' => $updates[2],
            'store' => $stores[1],
            'storeViewPrice' => $productUpdateForCustomWebsite->getPrice()
        ];
    }

    /**
     * Roll back configuration settings.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->testStepFactory->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $this->configData, 'rollback' => true]
        )->run();
    }
}
