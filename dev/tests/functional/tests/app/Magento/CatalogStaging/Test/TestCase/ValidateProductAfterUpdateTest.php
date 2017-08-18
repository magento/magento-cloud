<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\TestCase;

use Magento\Mtf\TestCase\Injectable;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Staging\Test\Fixture\Update;
use Magento\Mtf\TestStep\TestStepFactory;

/**
 * Preconditions:
 * 1. Create simple product enabled in Default and(or) Custom Stores.
 *
 * Steps:
 * 1. Create an update campaign for Default Store.
 * 2. Set another price for previously created campaign in Custom Store (optional).
 * 3. Run cron twice.
 * 4. Open simple product in Default and Custom Websites.
 * 5. Perform assertions.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-49851, MAGETWO-55004, MAGETWO-55074
 */
class ValidateProductAfterUpdateTest extends Injectable
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
     * Prepare data.
     *
     * @return void
     */
    public function __prepare()
    {
        if ($_ENV['magento_timezone'] !== 'UTC') {
            $this->markTestIncomplete(
                'The test will only pass if both Magento timezone and server timezone match '
                . 'and are both set to UTC because of the Staging module restrictions.'
            );
        }
    }

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
     * Create product updates and validate them in custom website.
     *
     * @param CatalogProductSimple $productUpdate
     * @param Update $update
     * @param CatalogProductSimple|null $productUpdateForCustomWebsite [optional]
     * @param string|null $configData [optional]
     * @return array
     */
    public function test(
        CatalogProductSimple $productUpdate,
        Update $update,
        CatalogProductSimple $productUpdateForCustomWebsite = null,
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
        $stores = $product->getDataFieldConfig('website_ids')['source']->getStores();
        $websites = $product->getDataFieldConfig('website_ids')['source']->getWebsites();
        if (isset($stores[1])) {
            $customStoreName = $stores[1]->getName();
            $customStoreGroup = $stores[1]->getGroupId();
        }

        // Test steps
        $this->catalogProductEdit->open(['id' => $product->getId()]);
        $this->catalogProductEdit->getProductScheduleBlock()->editUpdate($update->getName());
        $this->catalogProductEdit->getStagingFormPageActions()
            ->selectStoreView($stores[0]->getGroupId() . '/' . $stores[0]->getName());
        $this->catalogProductEdit->getModalBlock()->acceptAlert();
        $this->catalogProductEdit->getStagingProductForm()->fill($productUpdate);
        $this->catalogProductEdit->getStagingFormPageActions()->save();

        if (isset($customStoreName) && isset($customStoreGroup)) {
            $this->catalogProductEdit->getProductScheduleBlock()->editUpdate($update->getName());
            $this->catalogProductEdit->getStagingFormPageActions()
                ->selectStoreView($customStoreGroup . '/' . $customStoreName);
            $this->catalogProductEdit->getModalBlock()->acceptAlert();
            $this->catalogProductEdit->getStagingProductForm()->fill($productUpdateForCustomWebsite);
            $this->catalogProductEdit->getStagingFormPageActions()->save();
        }

        return [
            'product' => $product,
            'customWebsite' => isset($websites[1]) ? $websites[1] : null,
            'expectedPrice' =>
                $productUpdateForCustomWebsite != null ? $productUpdateForCustomWebsite->getPrice() : null,
            'expectedName' => $productUpdate->getName(),
            'productUpdate' => $productUpdate,
            'update' => $update
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
