<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\TestCase;

use Magento\Catalog\Test\Page\Adminhtml\CatalogCategoryIndex;
use Magento\CatalogEvent\Test\Fixture\CatalogEventEntity;
use Magento\Catalog\Test\Fixture\Category;
use Magento\CatalogEvent\Test\Page\Adminhtml\CatalogEventNew;
use Magento\Mtf\TestCase\Injectable;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Test Flow:
 * 1. Log in to backend as admin user.
 * 2. Navigate to Products>Inventory>Categories
 * 3. Select created category.
 * 4. Click 'Add Event' button.
 * 5. Fill in all data according to data set.
 * 6. Save Event.
 * 7. Navigate to category page again.
 * 8. Click 'Edit Event' button.
 * 9. Delete event from the event page.
 * 7. Perform all assertions.
 *
 * @group Catalog_Events
 * @ZephyrId MAGETWO-47633
 */
class ManageCatalogEventEntityFromCategoryPageTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Category Page
     *
     * @var CatalogCategoryIndex
     */
    protected $catalogCategoryIndex;

    /**
     * Catalog Event Page
     *
     * @var CatalogEventNew
     */
    protected $catalogEventNew;

    /**
     * @param CatalogCategoryIndex $catalogCategoryIndex
     * @param CatalogEventNew $catalogEventNew
     * @param Category $category
     *
     * @return array
     */
    public function __inject(
        CatalogCategoryIndex $catalogCategoryIndex,
        CatalogEventNew $catalogEventNew,
        Category $category
    ) {
        $this->catalogCategoryIndex = $catalogCategoryIndex;
        $this->catalogEventNew = $catalogEventNew;
        $category->persist();
        return ['category' => $category];
    }

    /**
     * Perform test.
     *
     * @param Category $category
     * @param CatalogEventEntity $catalogEvent
     * @return void
     */
    public function test(
        Category $category,
        CatalogEventEntity $catalogEvent
    ) {
        $this->catalogCategoryIndex->open();
        $this->catalogCategoryIndex->getTreeCategories()->selectCategory($category);
        $this->catalogCategoryIndex->getPageActionsEvent()->addCatalogEvent();
        $this->catalogEventNew->getEventForm()->fill($catalogEvent);
        $this->catalogEventNew->getPageActions()->save();
        $this->catalogCategoryIndex->open();
        $this->catalogCategoryIndex->getTreeCategories()->selectCategory($category);
        $this->catalogCategoryIndex->getPageActionsEvent()->editCatalogEvent();
        $this->catalogCategoryIndex->getPageActionsEvent()->delete();
        $this->catalogEventNew->getModalBlock()->acceptAlert();
    }
}
