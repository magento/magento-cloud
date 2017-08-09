<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductAttribute;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\TestStep\TestStepFactory;
use Magento\VisualMerchandiser\Test\Page\Adminhtml\CatalogCategoryMerchandiser;
use Magento\Catalog\Test\Fixture\Category;
use Magento\VisualMerchandiser\Test\TestStep\AssignProductsToCategoryStep;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser;

/**
 * Assert that in the category rules products are selected by admin values of options of dropdown attributes.
 */
class AssertCategoryRuleAttributesUseAdminValue extends AbstractConstraint
{
    /**
     * Message, shown if products weren\'t linked to category when in rules were used admin values of options.
     */
    const MESSAGE_FOR_ADMIN = 'Products with values of options for admin store must be linked to category.';

    /**
     * Message, shown if products were linked to category when in rules were used default store values of options.
     */
    const MESSAGE_FOR_DEFAULT = 'Products with values of options for default store mustn\'t be linked to category.';

    /**
     * Factory for creation Test Step.
     *
     * @var TestStepFactory
     */
    private $testStepFactory;

    /**
     * Catalog category edit page.
     *
     * @var CatalogCategoryMerchandiser
     */
    private $catalogCategoryEdit;

    /**
     * Products to check if they are in products grid.
     *
     * @var array
     */
    private $products;

    /**
     * Category fixture.
     *
     * @var Category
     */
    private $category;

    /**
     * Store fixture.
     *
     * @var \Magento\Store\Test\Fixture\Store
     */
    private $store;

    /**
     * Catalog Product Attribute fixtures.
     *
     * @var CatalogProductAttribute[]
     */
    private $attributes;

    /**
     * Assert that in the category rules products are selected by admin values of options of dropdown attributes.
     *
     * @param TestStepFactory $testStepFactory
     * @param CatalogCategoryMerchandiser $catalogCategoryEdit
     * @param Category $category
     * @param array $attribute
     * @param array $ruleConditions
     * @param \Magento\Store\Test\Fixture\Store $store
     * @param array $products
     * @return void
     */
    public function processAssert(
        TestStepFactory $testStepFactory,
        CatalogCategoryMerchandiser $catalogCategoryEdit,
        Category $category,
        array $attribute,
        array $ruleConditions,
        \Magento\Store\Test\Fixture\Store $store,
        array $products
    ) {
        $this->testStepFactory = $testStepFactory;
        $this->catalogCategoryEdit = $catalogCategoryEdit;
        $this->products = $products;
        $this->category = $category;
        $this->store = $store;
        $this->attributes = $attribute;
        $ruleConditionsAdmin = $ruleConditions;

        //Fill matching by rule table with values for default store view.
        /** @var  CatalogProductAttribute $attributeFixture */
        foreach ($this->attributes as $key => $attributeFixture) {
            $defaultStoreOptionValue = $ruleConditions[$key]['value'];
            $adminStoreOptionValue = $ruleConditions[$key]['value'];
            foreach ($attributeFixture->getOptions() as $option) {
                if ($option['admin'] == $adminStoreOptionValue) {
                    $defaultStoreOptionValue = $option['view'];
                    break;
                }
            }
            $ruleConditions[$key]['value'] = $defaultStoreOptionValue;
        }

        $this->processViewAssert($ruleConditions, false, self::MESSAGE_FOR_DEFAULT);

        //Fill matching by rule table with values for admin store.
        $this->processViewAssert($ruleConditionsAdmin, true, self::MESSAGE_FOR_ADMIN);
    }

    /**
     * Assert for given conditions.
     *
     * @param array $ruleConditions
     * @param bool $visible
     * @param string $message
     * @return void
     */
    private function processViewAssert(array $ruleConditions, $visible, $message)
    {
        $this->testStepFactory->create(
            AssignProductsToCategoryStep::class,
            [
                'category' => $this->category,
                'attribute' => $this->attributes,
                'ruleConditions' => $ruleConditions,
                'currentStore' => $this->store,
            ]
        )->run();
        $this->catalogCategoryEdit->getEditForm()->openSection('category_products');
        /** @var Merchandiser $merchandiser */
        $merchandiser = $this->catalogCategoryEdit->getMerchandiserApp();
        $merchandiser->openTab('mode_grid');
        $view = $merchandiser->getTab('mode_grid');
        /* @var \Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid\ProductGrid $grid */
        $grid = $view->getProductGrid();

        /* @var FixtureInterface $productFixture */
        foreach ($this->products as $productFixture) {
            \PHPUnit_Framework_Assert::assertEquals(
                $grid->isProductVisible($productFixture),
                $visible,
                $message
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'Attributes visible for Category Rules use admin values.';
    }
}
