<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\TestCase;

use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Mtf\TestCase\Injectable;
use Magento\Staging\Test\Fixture\Update;

/**
 * Preconditions:
 * 1. Create permanent or temporary product updates.
 *
 * Steps:
 * 1. Open the product in the admin panel.
 * 2. Remove the product update.
 * 3. Save the product update as a new one.
 * 4. Perform assertions.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-49059
 */
class SaveAsNewUpdateTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Page to update a product.
     *
     * @var CatalogProductEdit
     */
    protected $editProductPage;

    /**
     * Injection data.
     *
     * @param CatalogProductEdit $editProductPage
     * @return void
     */
    public function __inject(
        CatalogProductEdit $editProductPage
    ) {
        $this->editProductPage = $editProductPage;
    }

    /**
     * Remove product update and save it as a new one.
     *
     * @param Update $firstUpdate
     * @param Update $secondUpdate
     * @return array
     */
    public function test(Update $firstUpdate, Update $secondUpdate)
    {
        // Preconditions
        $firstUpdate->persist();

        // Test steps
        $this->editProductPage->open(['id' => $firstUpdate->getProduct()->getId()]);
        $this->editProductPage->getProductScheduleBlock()->editUpdate($firstUpdate->getName());
        $this->editProductPage->getStagingFormPageActions()->remove();
        $this->editProductPage->getSaveAsNewUpdateForm()->fill($secondUpdate);
        $this->editProductPage->getStagingRemovalPageActions()->save();

        return [
            'product' => $firstUpdate->getProduct(),
            'updates' => [$secondUpdate]
        ];
    }
}
