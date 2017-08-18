<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\TargetRule\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\TargetRule\Test\Fixture\TargetRule;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Preconditions:
 * 1. Test Category are created.
 * 2. Products are created (1 product per each category).
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
 * @ZephyrId MAGETWO-24686
 */
class CreateTargetRuleEntityTest extends AbstractTargetRuleEntityTest
{
    /* tags */
    const MVP = 'yes';
    const TEST_TYPE = 'extended_acceptance_test';
    /* end tags */

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __prepare(
        FixtureFactory $fixtureFactory
    ) {
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * Run create TargetRule entity test.
     *
     * @param FixtureFactory $fixtureFactory
     * @param TargetRule $targetRule
     * @param CatalogProductSimple $product
     * @param CatalogProductSimple|null $promotedProduct
     * @param string|null $conditionEntity
     * @param CustomerSegment|null $customerSegment
     * @param $promotedProductWithSameCategory
     * @return array
     */
    public function test(
        FixtureFactory $fixtureFactory,
        TargetRule $targetRule,
        CatalogProductSimple $product,
        CatalogProductSimple $promotedProduct = null,
        $conditionEntity = null,
        CustomerSegment $customerSegment = null,
        $promotedProductWithSameCategory = false
    ) {
        // Preconditions:
        $product->persist();

        if ($promotedProduct === null && $promotedProductWithSameCategory) {
            $promotedProduct = $this->fixtureFactory->createByCode(
                'catalogProductSimple',
                [
                    'dataset' => 'default',
                    'data' => [
                        'category_ids' => [
                            'category' => $product->getDataFieldConfig('category_ids')['source']->getCategories()[0]
                        ],
                    ],
                ]
            );
        } elseif ($promotedProduct === null) {
            $promotedProduct = $fixtureFactory->createByCode(
                'catalogProductSimple',
                [
                    'dataset' => 'default',
                    'data' => [
                        'attribute_set_id' => ['attribute_set' =>
                            $product->getDataFieldConfig('attribute_set_id')['source']->getAttributeSet()
                        ],
                    ],
                ]
            );
        }
        $promotedProduct->persist();
        if ($customerSegment->hasData()) {
            $customerSegment->persist();
        }
        $replace = $this->getReplaceData($product, $promotedProduct, $conditionEntity, $customerSegment);

        // Steps
        $this->targetRuleIndex->open();
        $this->targetRuleIndex->getGridPageActions()->addNew();
        $this->targetRuleNew->getTargetRuleForm()->fill($targetRule, null, $replace);
        $this->targetRuleNew->getPageActions()->save();

        // Prepare data for tear down
        $this->prepareTearDown($targetRule);

        return ['promotedProducts' => [$promotedProduct]];
    }
}
