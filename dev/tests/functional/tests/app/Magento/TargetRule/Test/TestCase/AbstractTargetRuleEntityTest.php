<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\TargetRule\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;
use Magento\Mtf\TestStep\TestStepFactory;
use Magento\TargetRule\Test\Fixture\TargetRule;
use Magento\TargetRule\Test\Page\Adminhtml\TargetRuleEdit;
use Magento\TargetRule\Test\Page\Adminhtml\TargetRuleIndex;
use Magento\TargetRule\Test\Page\Adminhtml\TargetRuleNew;
use Magento\Ui\Block\Component\StepsWizard\StepInterface;

/**
 * Parent class for TargetRule tests.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class AbstractTargetRuleEntityTest extends Injectable
{
    /**
     * Target rule grid page.
     *
     * @var TargetRuleIndex
     */
    protected $targetRuleIndex;

    /**
     * Target rule create page.
     *
     * @var TargetRuleNew
     */
    protected $targetRuleNew;

    /**
     * Target rule edit page.
     *
     * @var TargetRuleEdit
     */
    protected $targetRuleEdit;

    /**
     * Target rule entity name.
     *
     * @var string
     */
    protected $targetRuleName;

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Update attribute step factory.
     *
     * @var TestStepFactory
     */
    protected $stepFactory;

    /**
     * Update attribute step holder for tearDown().
     *
     * @var StepInterface
     */
    protected $updateAttributeStep = null;

    /**
     * Injection data.
     *
     * @param TargetRuleIndex $targetRuleIndex
     * @param TargetRuleNew $targetRuleNew
     * @param TargetRuleEdit $targetRuleEdit
     * @param FixtureFactory $fixtureFactory
     * @param TestStepFactory $stepFactory
     * @return void
     */
    public function __inject(
        TargetRuleIndex $targetRuleIndex,
        TargetRuleNew $targetRuleNew,
        TargetRuleEdit $targetRuleEdit,
        FixtureFactory $fixtureFactory,
        TestStepFactory $stepFactory
    ) {
        $this->targetRuleIndex = $targetRuleIndex;
        $this->targetRuleNew = $targetRuleNew;
        $this->targetRuleEdit = $targetRuleEdit;
        $this->fixtureFactory = $fixtureFactory;
        $this->stepFactory = $stepFactory;
    }

    /**
     * Prepare data for tear down.
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
     * Clear data after test.
     *
     * @return void
     */
    public function tearDown()
    {
        if (!empty($this->targetRuleName)) {
            $filter = ['name' => $this->targetRuleName];
            $this->targetRuleIndex->open();
            $this->targetRuleIndex->getTargetRuleGrid()->searchAndOpen($filter);
            $this->targetRuleEdit->getPageActions()->delete();
            $this->targetRuleEdit->getModalBlock()->acceptAlert();
            $this->targetRuleName = '';
        }
        if ($this->updateAttributeStep !== null) {
            $this->updateAttributeStep->cleanup();
        }
    }

    /**
     * Get data for replace in variations.
     *
     * @param CatalogProductSimple $product
     * @param CatalogProductSimple $promotedProduct
     * @param string $conditionEntity
     * @param CustomerSegment|null $customerSegment
     * @return array
     */
    protected function getReplaceData(
        CatalogProductSimple $product,
        CatalogProductSimple $promotedProduct,
        $conditionEntity = 'category',
        CustomerSegment $customerSegment = null
    ) {
        $customerSegmentName = ($customerSegment && $customerSegment->hasData()) ? $customerSegment->getName() : '';
        switch ($conditionEntity) {
            case 'attribute':
                /** @var \Magento\Catalog\Test\Fixture\CatalogProductAttribute[] $attrs */
                $attributes = $product->getDataFieldConfig('attribute_set_id')['source']
                    ->getAttributeSet()->getDataFieldConfig('assigned_attributes')['source']->getAttributes();
                $replaceData = [
                    'rule_information' => [
                        'customer_segment_ids' => [
                            '%customer_segment%' => $customerSegmentName,
                        ],
                    ],
                    'products_to_match' => [
                        'conditions_serialized' => [
                            '%attribute_name%' => $attributes[0]->getFrontendLabel(),
                            '%attribute_value%' => $attributes[0]->getOptions()[0]['view'],
                        ],
                    ],
                    'products_to_display' => [
                        'actions_serialized' => [
                            '%attribute_name%' => $attributes[0]->getFrontendLabel(),
                        ],
                    ],
                ];
                break;
            case 'sku':
                $replaceData = [
                    'rule_information' => [
                        'customer_segment_ids' => [
                            '%customer_segment%' => $customerSegmentName,
                        ],
                    ],
                    'products_to_match' => [
                        'conditions_serialized' => [
                            '%sku%' => $product->getSku(),
                        ],
                    ],
                    'products_to_display' => [
                        'actions_serialized' => [
                            '%promoted_sku%' => $promotedProduct->getSku(),
                        ],
                    ],
                ];
                break;
            case 'category':
            default:
                $sourceCategory = $product->getDataFieldConfig('category_ids')['source'];
                $sourceRelatedCategory = $promotedProduct->getDataFieldConfig('category_ids')['source'];
                $replaceData = [
                    'rule_information' => [
                        'customer_segment_ids' => [
                            '%customer_segment%' => $customerSegmentName,
                        ],
                    ],
                    'products_to_match' => [
                        'conditions_serialized' => [
                            '%category_1%' => $sourceCategory->getIds()[0],
                        ],
                    ],
                    'products_to_display' => [
                        'actions_serialized' => [
                            '%category_2%' => $sourceRelatedCategory->getIds()[0],
                        ],
                    ],
                ];
                break;
        }

        return $replaceData;
    }
}
