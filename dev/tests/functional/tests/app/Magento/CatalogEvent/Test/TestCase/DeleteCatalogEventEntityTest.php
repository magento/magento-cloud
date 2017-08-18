<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\TestCase;

use Magento\Catalog\Test\Fixture\Category;
use Magento\Mtf\TestCase\Injectable;
use Magento\CatalogEvent\Test\Fixture\CatalogEventEntity;
use Magento\CatalogEvent\Test\Page\Adminhtml\CatalogEventNew;
use Magento\CatalogEvent\Test\Page\Adminhtml\CatalogEventIndex;

/**
 * Test Flow:
 * 1. Log in to backend as admin user.
 * 2. Navigate to MARKETING>Private Sales>Events.
 * 3. Choose catalog event from precondition.
 * 4. Click "Delete" button.
 * 5. Perform all assertions.
 *
 * @group Catalog_Events
 * @ZephyrId MAGETWO-23418
 */
class DeleteCatalogEventEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Catalog Event Page.
     *
     * @var CatalogEventNew
     */
    private $catalogEventNew;

    /**
     * Event Page.
     *
     * @var CatalogEventIndex
     */
    private $catalogEventIndex;

    /**
     * Prepare required dependencies for test.
     *
     * @param CatalogEventNew $catalogEventNew
     * @param CatalogEventIndex $catalogEventIndex
     * @return void
     */
    public function __prepare(
        CatalogEventNew $catalogEventNew,
        CatalogEventIndex $catalogEventIndex
    ) {
        $this->catalogEventNew = $catalogEventNew;
        $this->catalogEventIndex = $catalogEventIndex;
    }

    /**
     * Delete Catalog Event Entity test.
     *
     * @param CatalogEventEntity $catalogEvent
     * @return array
     */
    public function test(CatalogEventEntity $catalogEvent)
    {
        // Precondition
        $catalogEvent->persist();

        //Steps
        $this->catalogEventIndex->open();
        $this->catalogEventIndex->getEventGrid()->searchAndOpen(['category_name' => $catalogEvent->getCategoryId()]);
        $this->catalogEventNew->getPageActions()->delete();
        $this->catalogEventNew->getModalBlock()->acceptAlert();

        /** @var Category $category */
        $category = $catalogEvent->getDataFieldConfig('category_id')['source']->getCategory();
        $product = current($category->getDataFieldConfig('category_products')['source']->getProducts());
        return ['product' => $product, 'category' => $category];
    }
}
