<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\TestCase;

use Magento\Mtf\TestCase\Scenario;

/**
 * Steps:
 * 1. Create product attribute.
 * 2. Assign created attribute to Default attribute set.
 * 3. Add crated attribute to Visual Merchandiser Visible Attributes for Category Rules.
 * 4. Create simple product and fill created attribute.
 * 5. Create category in catalog/category.
 * 6. Add condition in Products in Category collapsible panel in category to match created product to category.
 * 7. Save category.
 * 8. Go to frontend to created category.
 * 9. Perform appropriate assertions.
 *
 * @group VisualMerchandiser (MX)
 * @ZephyrId MAGETWO-69734
 */
class AddProductsTest extends Scenario
{
    public function test()
    {
        $this->executeScenario();
    }
}
