<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\CatalogEvent\Test\Fixture\CatalogEventEntity;
use Magento\CatalogEvent\Test\Page\Adminhtml\CatalogEventIndex;
use Magento\CatalogEvent\Test\Page\Adminhtml\CatalogEventNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test Creation for CreateCatalogEventEntity from Catalog Event Page
 *
 * Test Flow:
 * 1. Log in to backend as admin user.
 * 2. Navigate to MARKETING>Private Sales>Events.
 * 3. Start new Event creation.
 * 4. Choose category from precondition.
 * 5. Fill in all data according to data set.
 * 6. Save Event.
 * 7. Perform all assertions.
 *
 * @group Catalog_Events
 * @ZephyrId MAGETWO-24573
 */
class CreateCatalogEventEntityFromCatalogEventPageTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const TEST_TYPE = 'extended_acceptance_test';
    /* end tags */

    /**
     * Catalog Event Page
     *
     * @var CatalogEventNew
     */
    protected $catalogEventNew;

    /**
     * Catalog Event Page on the Backend
     *
     * @var CatalogEventIndex
     */
    protected $catalogEventIndex;

    /**
     * @param FixtureFactory $fixtureFactory
     * @param CatalogEventIndex $catalogEventIndex
     * @param CatalogEventNew $catalogEventNew
     * @return array
     */
    public function __inject(
        CatalogEventIndex $catalogEventIndex,
        CatalogEventNew $catalogEventNew,
        FixtureFactory $fixtureFactory
    ) {
        $this->catalogEventIndex = $catalogEventIndex;
        $this->catalogEventNew = $catalogEventNew;

        /**@var CatalogProductSimple $catalogProductSimple */
        $product = $fixtureFactory->createByCode(
            'catalogProductSimple',
            ['dataset' => 'product_with_category']
        );
        $product->persist();

        return [
            'product' => $product
        ];
    }

    /**
     * Create Catalog Event Entity from Catalog Event page
     *
     * @param CatalogEventEntity $catalogEvent
     * @param CatalogProductSimple $product
     * @return void
     */
    public function testCreateCatalogEventFromEventPage(
        CatalogEventEntity $catalogEvent,
        CatalogProductSimple $product
    ) {
        //Steps
        $this->catalogEventIndex->open();
        $this->catalogEventIndex->getPageActions()->addNew();
        $category = $product->getDataFieldConfig('category_ids')['source']->getCategories()[0];
        $this->catalogEventNew->getTreeCategories()->selectCategory($category->getPath() . '/' . $category->getName());
        $this->catalogEventNew->getEventForm()->fill($catalogEvent);
        $this->catalogEventNew->getPageActions()->save();
    }
}
