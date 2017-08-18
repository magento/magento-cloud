<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\TestCase;

use Magento\Mtf\TestCase\Injectable;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Catalog\Test\Block\Adminhtml\Product\Edit\Section\AdvancedPricing;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Staging\Test\Fixture\Update;

/**
 * Preconditions:
 * 1. Create atleast two simple products with special price
 *
 * Steps:
 * 1. Create a scheduled update for the second product
 * 2. In the staging product form, set a special price for this product different than the one from preConditions
 * 3. Run cron three times to force update
 * 4. Go to storefront
 * 5. Perform assertions for price for all the products
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-67766
 */

class ValidateProductSpecialPriceAfterUpdateAppliedTest extends Injectable
{
    /**
     * Catalog Product Edit page
     *
     * @var CatalogProductEdit
     */
    private $catalogProductEdit;

    /**
     * Perform the needed injection
     *
     * @param CatalogProductEdit $catalogProductEdit
     * @return void
     */

    public function __inject(CatalogProductEdit $catalogProductEdit)
    {
        $this->catalogProductEdit = $catalogProductEdit;
    }

    public function test(
        CatalogProductSimple $firstProduct,
        Update $update,
        array $prices
    ) {
        $firstProduct->persist();
        $update->persist();
        $secondProduct = $update->getProduct();
        $this->catalogProductEdit->open(['id'=> $secondProduct->getId()]);
        $this->catalogProductEdit->getProductScheduleBlock()->editUpdate($update->getName());

        $this->catalogProductEdit->getStagingProductForm()
            ->openSection('staging-advanced-pricing')
            ->getSection('staging-advanced-pricing')
            ->setFieldsData($prices);
        $this->catalogProductEdit->getStagingFormPageActions()->save();
        $this->catalogProductEdit->getFormPageActions()->save();

        return[
            'firstProduct' => $firstProduct,
            'update' => $update,
        ];
    }
}
