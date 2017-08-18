<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\TargetRule\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\TargetRule\Test\Fixture\TargetRule;

/**
 * Preconditions:
 * 1. Target Rule is created.
 *
 * Steps:
 * 1. Login to backend.
 * 2. Navigate to MARKETING > Related Products Rules.
 * 3. Click Target Rule from grid.
 * 4. Edit test value(s) according to dataset.
 * 5. Click 'Save' button.
 * 6. Perform all asserts.
 *
 * @group Target_Rules
 * @ZephyrId MAGETWO-24807
 */
class UpdateTargetRuleEntityTest extends AbstractTargetRuleEntityTest
{
    /* tags */
    const MVP = 'yes';
    /* end tags */

    /**
     * Run update TargetRule entity test.
     *
     * @param CatalogProductSimple $product
     * @param CatalogProductSimple $promotedProduct
     * @param TargetRule $initialTargetRule
     * @param TargetRule $targetRule
     * @param string|null $conditionEntity
     * @param CustomerSegment|null $customerSegment
     * @return array
     */
    public function testUpdateTargetRuleEntity(
        CatalogProductSimple $product,
        CatalogProductSimple $promotedProduct,
        TargetRule $initialTargetRule,
        TargetRule $targetRule,
        $conditionEntity = null,
        CustomerSegment $customerSegment = null
    ) {
        // Preconditions:
        $product->persist();
        $promotedProduct->persist();
        $initialTargetRule->persist();
        if ($customerSegment && $customerSegment->hasData()) {
            $customerSegment->persist();
        }
        $replace = $this->getReplaceData($product, $promotedProduct, $conditionEntity, $customerSegment);

        // Steps
        $filter = ['name' => $initialTargetRule->getName()];
        $this->targetRuleIndex->open();
        $this->targetRuleIndex->getTargetRuleGrid()->searchAndOpen($filter);
        $this->targetRuleNew->getTargetRuleForm()->fill($targetRule, null, $replace);
        $this->targetRuleNew->getPageActions()->save();

        // Prepare data for tear down
        $this->prepareTearDown($targetRule, $initialTargetRule);

        return ['promotedProducts' => [$promotedProduct]];
    }
}
