<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogAttributeSet;
use Magento\Catalog\Test\Fixture\CatalogProductAttribute;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductSetEdit;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductSetIndex;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Staging\Test\Fixture\Update;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test Creation scheduled update on Simple Product after attribute group has been changed.
 *
 * Test Flow:
 * 1. Go to the Admin -> Stores -> Attribute Set.
 * 2. Edit Default attribute set.
 * 3. Create new attribute group.
 * 4. Move attribute to the new attribute group.
 * 5. Create simple product.
 * 6. Create schedule update for the product.
 *
 * @ZephyrId MAGETWO-69786
 */
class CreateScheduleUpdateAfterAttributePriceMovedToNewGroupEntityTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    const DOMAIN = 'MX';
    /* end tags */

    /**
     * Catalog Product Set page.
     *
     * @var CatalogProductSetIndex
     */
    private $productSetIndex;

    /**
     * Catalog Product Set edit page.
     *
     * @var CatalogProductSetEdit
     */
    private $productSetEdit;

    /**
     * Catalog product edit page.
     *
     * @var CatalogProductEdit
     */
    private $catalogProductEdit;

    /**
     * Fixture CatalogAttributeSet.
     *
     * @var CatalogAttributeSet
     */
    private $attributeSet;

    /**
     * Fixture CatalogProductAttribute.
     *
     * @var CatalogProductAttribute
     */
    private $productAttribute;

    /**
     * Inject data
     *
     * @param CatalogProductSetIndex $productSetIndex
     * @param CatalogProductSetEdit $productSetEdit
     * @param CatalogProductEdit $catalogProductEdit
     * @return void
     */
    public function __inject(
        CatalogProductSetIndex $productSetIndex,
        CatalogProductSetEdit $productSetEdit,
        CatalogProductEdit $catalogProductEdit
    ) {
        $this->productSetIndex = $productSetIndex;
        $this->productSetEdit = $productSetEdit;
        $this->catalogProductEdit = $catalogProductEdit;
    }

    /**
     * Run UpdateAttributeSet test.
     *
     * @param CatalogAttributeSet $attributeSet
     * @param CatalogProductAttribute $productAttribute
     * @param CatalogProductSimple $product
     * @param Update $update
     * @return array
     */
    public function test(
        CatalogAttributeSet $attributeSet,
        CatalogProductAttribute $productAttribute,
        CatalogProductSimple $product,
        Update $update
    ) {
        $this->attributeSet = $attributeSet;
        $this->productAttribute = $productAttribute;

        // Preconditions.
        $product->persist();

        // Steps.
        $filter = ['set_name' => $this->attributeSet->getAttributeSetName()];
        $this->productSetIndex->open()->getGrid()->searchAndOpen($filter);

        // Create new group.
        $groupName = $this->attributeSet->getGroup();
        $this->productSetEdit->getAttributeSetEditBlock()->addAttributeSetGroup($groupName);

        // Move attribute to the new group.
        $this->productSetEdit->getAttributeSetEditBlock()
            ->moveAttribute($this->productAttribute->getData(), $groupName);
        $this->productSetEdit->getPageActions()->save();

        // Add new scheduled update.
        $this->catalogProductEdit->open(['id' => $product->getId()]);
        $this->catalogProductEdit->getProductScheduleBlock()->clickScheduleNewUpdate();
        $this->catalogProductEdit->getProductScheduleForm()->fill($update);
        $this->catalogProductEdit->getStagingFormPageActions()->save();

        return [
            'updates' => [$update]
        ];
    }

    /**
     * Clear data after test.
     *
     * @return void
     */
    public function tearDown()
    {
        if (empty($this->attributeSet)) {
            return;
        }
        $filter = ['set_name' => $this->attributeSet->getAttributeSetName()];
        $this->productSetIndex->open()->getGrid()->searchAndOpen($filter);

        // Move attribute to the Product Details group.
        $this->productSetEdit->getAttributeSetEditBlock()
            ->moveAttribute($this->productAttribute->getData(), 'Product Details');
        $this->productSetEdit->getPageActions()->save();
    }
}
