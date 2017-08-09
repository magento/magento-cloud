<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\TestStep;

use Magento\Catalog\Test\Fixture\CatalogProductAttribute;
use Magento\Catalog\Test\Fixture\Category;
use Magento\Catalog\Test\Page\Adminhtml\CatalogCategoryIndex;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section\SmartCategoryGrid;
use Magento\VisualMerchandiser\Test\Page\Adminhtml\CatalogCategoryMerchandiser;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section\SmartCategoryBlock;

/**
 * Assign products to category by category rules step.
 */
class AssignProductsToCategoryStep implements TestStepInterface
{
    /**
     * Category edit page.
     *
     * @var CatalogCategoryMerchandiser
     */
    private $catalogCategoryEdit;

    /**
     * Category index page.
     *
     * @var CatalogCategoryIndex
     */
    private $catalogCategoryIndex;

    /**
     * Store fixture.
     *
     * @var \Magento\Store\Test\Fixture\Store
     */
    private $currentStore;

    /**
     * Category fixture.
     *
     * @var Category
     */
    private $category;

    /**
     * Catalog Product Attribute fixtures.
     *
     * @var CatalogProductAttribute[]
     */
    private $attributes;

    /**
     * Conditions to select products.
     *
     * @var array
     */
    private $ruleConditions;

    /**
     * @param CatalogCategoryIndex $catalogCategoryIndex
     * @param CatalogCategoryMerchandiser $catalogCategoryEdit
     * @param Category $category
     * @param array $attribute
     * @param array $ruleConditions
     * @param \Magento\Store\Test\Fixture\Store|null $currentStore
     * @return void
     */
    public function __construct(
        CatalogCategoryIndex $catalogCategoryIndex,
        CatalogCategoryMerchandiser $catalogCategoryEdit,
        Category $category,
        array $attribute,
        array $ruleConditions,
        $currentStore = null
    ) {
        $this->catalogCategoryIndex = $catalogCategoryIndex;
        $this->catalogCategoryEdit = $catalogCategoryEdit;
        $this->currentStore = $currentStore;
        $this->category = $category;
        $this->attributes = $attribute;
        $this->ruleConditions = $ruleConditions;
    }

    /**
     * Assign products.
     *
     * @return void
     */
    public function run()
    {
        $this->catalogCategoryIndex->open();

        if ($this->currentStore) {
            $storeName = $this->currentStore->getName();
            $this->catalogCategoryEdit->getFormPageActions()->selectStoreView($storeName);
        }

        $this->catalogCategoryIndex->getTreeCategories()->selectCategory($this->category);
        $this->catalogCategoryEdit->getEditForm()->openSection('category_products');
        /** @var SmartCategoryBlock $smartCategorySwitcherBlock */
        $smartCategorySwitcherBlock = $this->catalogCategoryEdit
            ->getEditForm()
            ->getSection('category_products')
            ->getSmartCategoryBlock();
        $smartCategorySwitcherBlock->setMatchProductsByRuleValue('Yes');

        $dataForSmartCategory = [];
        foreach ($this->attributes as $key => $attribute) {
            $dataForSmartCategory[] = [
                'attribute' => $attribute->getFrontendLabel(),
                'operator' => $this->ruleConditions[$key]['operator'],
                'value' => $this->ruleConditions[$key]['value'],
                'logic' => isset($this->ruleConditions[$key]['logic']) ? $this->ruleConditions[$key]['logic'] : 'AND'
            ];
        }

        /** @var SmartCategoryGrid $smartCategoryGrid */
        $smartCategoryGrid = $smartCategorySwitcherBlock->getSmartCategoryGrid();
        $smartCategoryGrid->fillConditions($dataForSmartCategory);

        $this->catalogCategoryEdit->getFormPageActions()->save();
    }
}
