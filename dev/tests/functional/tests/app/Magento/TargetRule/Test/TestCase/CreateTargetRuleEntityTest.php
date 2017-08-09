<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\TargetRule\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductAttribute;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\TargetRule\Test\Fixture\TargetRule;
use Magento\TargetRule\Test\TestStep\UpdateAttributeStep;

/**
 * Preconditions:
 * 1. Test Category are created.
 * 2. Products are created (1 product per each category).
 *
 * Steps:
 * 1. Log in as default admin user.
 * 2. Go to Marketing > Related Products Rules
 * 3. Click 'Add Rule' button.
 * 4. Fill in data according to dataset
 * 5. Save Related Products Rule.
 * 6. Perform all assertions.
 *
 * @group Target_Rules_(MX)
 * @ZephyrId MAGETWO-69548
 */
class CreateTargetRuleEntityTest extends AbstractTargetRuleEntityTest
{
    /* tags */
    const MVP = 'yes';
    const DOMAIN = 'MX';
    const TEST_TYPE = 'extended_acceptance_test';
    /* end tags */

    /**
     * Run create TargetRule entity test.
     *
     * @param CatalogProductSimple $product
     * @param TargetRule $targetRule
     * @param CatalogProductSimple $promotedProduct
     * @param CustomerSegment $customerSegment
     * @param string $conditionEntity
     * @return array
     */
    public function testCreateTargetRuleEntity(
        CatalogProductSimple $product,
        TargetRule $targetRule,
        CatalogProductSimple $promotedProduct = null,
        $conditionEntity = 'category',
        CustomerSegment $customerSegment = null
    ) {
        // Preconditions:
        $product->persist();
        if ($promotedProduct === null) {
            $promotedProduct = $this->fixtureFactory->createByCode(
                'catalogProductSimple',
                [
                    'dataset' => 'default',
                    'data' => [
                        'attribute_set_id' => [
                            'attribute_set' => $product->getDataFieldConfig('attribute_set_id')['source']
                                ->getAttributeSet()
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
        if ($conditionEntity == 'sku') {
            $this->updateAttributeStep = $this->stepFactory->create(
                UpdateAttributeStep::class,
                [
                    'attributeCode' => 'sku',
                    'updateAttributeData' => ['is_used_for_promo_rules' => 'Yes'],
                ]
            );
            $this->updateAttributeStep->run();
        }
        $this->targetRuleIndex->open();
        $this->targetRuleIndex->getGridPageActions()->addNew();
        $this->targetRuleNew->getTargetRuleForm()->fill($targetRule, null, $replace);
        $this->targetRuleNew->getPageActions()->save();

        // Prepare data for tear down
        $this->prepareTearDown($targetRule);

        return ['promotedProducts' => [$promotedProduct]];
    }
}
