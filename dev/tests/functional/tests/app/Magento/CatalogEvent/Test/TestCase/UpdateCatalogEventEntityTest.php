<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Fixture\Product\CategoryIds;
use Magento\CatalogEvent\Test\Fixture\CatalogEventEntity;
use Magento\CatalogEvent\Test\Page\Adminhtml\CatalogEventIndex;
use Magento\CatalogEvent\Test\Page\Adminhtml\CatalogEventNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test Creation for Update CatalogEventEntity
 *
 * Preconditions:
 * 1. Subcategory is created.
 * 2. Product is created and assigned to subcategory.
 * 3. Catalog event is created and applied for existing category.
 *
 * Test Flow:
 * 1. Log in to backend as admin user.
 * 2. Navigate to MARKETING>Private Sales>Events.
 * 3. Open existing catalog event
 * 4. Fill in all data according to data set
 * 5. Save Event.
 * 6. Perform all assertions.
 *
 * @group Catalog_Events
 * @ZephyrId MAGETWO-24576
 */
class UpdateCatalogEventEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Catalog Event Page
     *
     * @var CatalogEventNew
     */
    protected $catalogEventNew;

    /**
     * Catalog Product fixture
     *
     * @var CatalogProductSimple
     */
    protected $product;

    /**
     * Event Page
     *
     * @var CatalogEventIndex
     */
    protected $catalogEventIndex;

    /**
     * Inject data
     *
     * @param CatalogEventNew $catalogEventNew
     * @param CatalogEventIndex $catalogEventIndex
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __inject(
        CatalogEventNew $catalogEventNew,
        CatalogEventIndex $catalogEventIndex,
        FixtureFactory $fixtureFactory
    ) {
        $this->catalogEventNew = $catalogEventNew;
        $this->catalogEventIndex = $catalogEventIndex;

        /** @var CatalogProductSimple $product */
        $product = $fixtureFactory->createByCode('catalogProductSimple', ['dataset' => 'product_with_category']);
        $product->persist();
        $this->product = $product;

        /** @var CategoryIds $sourceCategories */
        $sourceCategory = $product->getDataFieldConfig('category_ids')['source']->getCategories()[0];
        $catalogEvent = $fixtureFactory->createByCode(
            'catalogEventEntity',
            [
                'dataset' => 'default',
                'data' => ['category_id' => $sourceCategory->getId()],
            ]
        );
        $catalogEvent->persist();

        return [
            'product' => $product,
            'catalogEventOriginal' => $catalogEvent,
        ];
    }

    /**
     * Update Catalog Event Entity
     *
     * @param CatalogEventEntity $catalogEvent
     * @return void
     */
    public function testUpdateCatalogEvent(CatalogEventEntity $catalogEvent)
    {
        $filter = [
            'category_name' => $this->product->getCategoryIds()[0],
        ];

        //Steps
        $this->catalogEventIndex->open();
        $this->catalogEventIndex->getEventGrid()->searchAndOpen($filter);
        $this->catalogEventNew->getEventForm()->fill($catalogEvent);
        $this->catalogEventNew->getPageActions()->save();
    }
}
