<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\TestCase;

use Magento\Mtf\TestCase\Injectable;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Staging\Test\Fixture\Update;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Mtf\TestStep\TestStepFactory;

/**
 * Preconditions:
 * 1. Create simple product with existing product update campaign.
 *
 * Steps:
 * 1. Open product in the admin panel.
 * 2. Open existing product update campaign.
 * 3. Remove that campaign.
 * 4. Perform assertions.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-50265, MAGETWO-50267
 */
class DeleteSingleProductUpdateTest extends Injectable
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
     * @param TestStepFactory $testStepFactory
     * @return void
     */
    public function __inject(
        CatalogProductEdit $catalogProductEdit,
        TestStepFactory $testStepFactory
    ) {
        $this->catalogProductEdit = $catalogProductEdit;
        $this->testStepFactory = $testStepFactory;
    }

    /**
     * Delete single product update and verify it.
     *
     * @param Update $update
     * @param CatalogProductSimple $productUpdate
     * @param bool $validateMessage [optional]
     * @param string|null $configData [optional]
     * @return array
     */
    public function test(
        Update $update,
        CatalogProductSimple $productUpdate,
        $validateMessage = false,
        $configData = null
    ) {
        // Preconditions
        $this->configData = $configData;
        $this->testStepFactory->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $this->configData]
        )->run();
        $update->persist();
        $product = $update->getProduct();
        $websites = $product->getDataFieldConfig('website_ids')['source']->getWebsites();
        $stores = $product->getDataFieldConfig('website_ids')['source']->getStores();
        if (isset($stores[1])) {
            $customStoreName = $stores[1]->getName();
            $customStoreGroup = $stores[1]->getGroupId();
        }

        // Test steps
        $this->catalogProductEdit->open(['id' => $product->getId()]);
        $this->catalogProductEdit->getProductScheduleBlock()->editUpdate($update->getName());
        if (isset($customStoreName) && isset($customStoreGroup)) {
            $this->catalogProductEdit->getStagingFormPageActions()
                ->selectStoreView($customStoreGroup . '/' . $customStoreName);
            $this->catalogProductEdit->getModalBlock()->acceptAlert();
        }
        $this->catalogProductEdit->getStagingProductForm()->fill($productUpdate);
        $this->catalogProductEdit->getStagingFormPageActions()->save();
        $this->catalogProductEdit->getProductScheduleBlock()->editUpdate($update->getName());
        $this->catalogProductEdit->getStagingFormPageActions()->remove();
        $this->catalogProductEdit->getUpdateDeleteBlock()->clickDelete();
        if ($validateMessage === false) {
            $this->catalogProductEdit->getStagingRemovalPageActions()->save();
        }

        return [
            'product' => $product,
            'expectedName' => $product->getName(),
            'customWebsite' => isset($websites[1]) ? $websites[1] : null,
            'expectedPrice' => $product->getPrice(),
            'update' => $update,
            'productUpdate' => $productUpdate,
            'updates' => [$update]
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
