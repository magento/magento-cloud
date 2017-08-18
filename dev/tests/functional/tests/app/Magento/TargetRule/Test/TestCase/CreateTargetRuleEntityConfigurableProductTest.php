<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\TargetRule\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\ConfigurableProduct\Test\Fixture\ConfigurableProduct;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\Mtf\Fixture\InjectableFixture;
use Magento\Mtf\TestCase\Injectable;
use Magento\TargetRule\Test\Fixture\TargetRule;
use Magento\TargetRule\Test\Page\Adminhtml\TargetRuleEdit;
use Magento\TargetRule\Test\Page\Adminhtml\TargetRuleIndex;
use Magento\TargetRule\Test\Page\Adminhtml\TargetRuleNew;

/**
 * Preconditions:
 * 1. Simple product is created
 * 2. Attribute for Configurable product is created
 * 3. Configurable product is created
 *
 * Steps:
 * 1. Log in as default admin user.
 * 2. Go to Marketing > Related Products Rules.
 * 3. Click 'Add Rule' button.
 * 4. Fill in data according to dataset.
 * 5. Save Related Products Rule.
 * 6. Perform all assertions.
 *
 * @group Target_Rules
 * @ZephyrId MAGETWO-64447
 */
class CreateTargetRuleEntityConfigurableProductTest extends Injectable
{
    /**
     * Target rule grid page
     *
     * @var TargetRuleIndex
     */
    protected $targetRuleIndex;

    /**
     * Target rule create page
     *
     * @var TargetRuleNew
     */
    protected $targetRuleNew;

    /**
     * Target rule edit page
     *
     * @var TargetRuleEdit
     */
    protected $targetRuleEdit;

    /**
     * Target rule entity name
     *
     * @var string
     */
    protected $targetRuleName;

    /**
     * Injection data
     *
     * @param TargetRuleIndex $targetRuleIndex
     * @param TargetRuleNew $targetRuleNew
     * @param TargetRuleEdit $targetRuleEdit
     * @return void
     */
    public function __inject(
        TargetRuleIndex $targetRuleIndex,
        TargetRuleNew $targetRuleNew,
        TargetRuleEdit $targetRuleEdit
    ) {
        $this->targetRuleIndex = $targetRuleIndex;
        $this->targetRuleNew = $targetRuleNew;
        $this->targetRuleEdit = $targetRuleEdit;
    }

    /**
     * Prepare data for tear down
     *
     * @param TargetRule $targetRule
     * @param TargetRule $targetRuleInitial
     * @return void
     */
    public function prepareTearDown(TargetRule $targetRule, TargetRule $targetRuleInitial = null)
    {
        $this->targetRuleName = $targetRule->hasData('name') ? $targetRule->getName() : $targetRuleInitial->getName();
    }

    /**
     * Clear data after test
     *
     * @return void
     */
    public function tearDown()
    {
        if (empty($this->targetRuleName)) {
            return;
        }
        $filter = ['name' => $this->targetRuleName];
        $this->targetRuleIndex->open();
        $this->targetRuleIndex->getTargetRuleGrid()->searchAndOpen($filter);
        $this->targetRuleEdit->getPageActions()->delete();
        $this->targetRuleEdit->getModalBlock()->acceptAlert();
        $this->targetRuleName = '';
    }

    /**
     * Run create TargetRule entity test
     *
     * @param TargetRule $targetRule
     * @param CatalogProductSimple $product
     * @param ConfigurableProduct $promotedProduct
     * @param CustomerSegment|null $customerSegment
     * @param null $conditionEntity
     * @return array
     */
    public function test(
        TargetRule $targetRule,
        CatalogProductSimple $product,
        ConfigurableProduct $promotedProduct,
        CustomerSegment $customerSegment = null,
        $conditionEntity = null
    ) {
        // Preconditions
        $product->persist();
        $promotedProduct->persist();

        if ($customerSegment->hasData()) {
            $customerSegment->persist();
        }

        $replace = $this->getReplaceData($promotedProduct, $conditionEntity, $customerSegment);

        // Steps
        $this->targetRuleIndex->open();
        $this->targetRuleIndex->getGridPageActions()->addNew();
        $this->targetRuleNew->getTargetRuleForm()->fill($targetRule, null, $replace);
        $this->targetRuleNew->getPageActions()->save();

        // Prepare data for tear down
        $this->prepareTearDown($targetRule);

        return [
            'promotedProducts' => [
                $promotedProduct,
            ],
        ];
    }

    /**
     * Get data for replace in variations
     *
     * @param InjectableFixture $product
     * @param string|null $conditionEntity
     * @param CustomerSegment|null $customerSegment
     * @return array
     */
    protected function getReplaceData(
        InjectableFixture $product,
        $conditionEntity = null,
        CustomerSegment $customerSegment = null
    ) {
        $customerSegmentName = ($customerSegment && $customerSegment->hasData()) ? $customerSegment->getName() : '';

        return [
            'rule_information' => [
                'customer_segment_ids' => [
                    '%customer_segment%' => $customerSegmentName,
                ],
            ],
            'products_to_display' =>
                $this->getProductsConditionsData($product, $conditionEntity)['products_to_display'],
        ];
    }

    /**
     * Get product attribute
     *
     * @param InjectableFixture $product
     * @return mixed
     */
    private function getAttribute(InjectableFixture $product)
    {
        $attributes = $product->getDataFieldConfig('configurable_attributes_data')['source']->getAttributes();
        if (is_array($attributes)) {
            $attribute = current($attributes);
        }
        return isset($attribute) ? $attribute : null;
    }

    /**
     * Get products conditions data for replace in variations
     *
     * @param InjectableFixture $product
     * @param string|null $conditionEntity
     * @return array
     */
    private function getProductsConditionsData(
        InjectableFixture $product,
        $conditionEntity = null
    ) {
        $result = [];
        switch ($conditionEntity) {
            case 'attribute':
                $attribute = $this->getAttribute($product);
                if ($attribute) {
                    $result['products_to_display'] = [
                        'actions_serialized' => [
                            '%attribute_name%' => $attribute->getFrontendLabel(),
                            '%attribute_value%' => $attribute->getOptions()[0]['view'],
                        ],
                    ];
                }
                break;
        }
        return $result;
    }
}
