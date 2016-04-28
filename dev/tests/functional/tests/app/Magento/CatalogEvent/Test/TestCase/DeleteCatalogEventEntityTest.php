<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Fixture\Product\CategoryIds;
use Magento\CatalogEvent\Test\Page\Adminhtml\CatalogEventIndex;
use Magento\CatalogEvent\Test\Page\Adminhtml\CatalogEventNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test Creation for Delete CatalogEventEntity
 *
 * Test Flow:
 * 1. Log in to backend as admin user.
 * 2. Navigate to MARKETING>Private Sales>Events.
 * 3. Choose catalog event from precondition.
 * 4. Click "Delete" button.
 * 5. Perform all assertions.
 *
 * @group Catalog_Events_(MX)
 * @ZephyrId MAGETWO-23418
 */
class DeleteCatalogEventEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'MX';
    /* end tags */

    /**
     * Catalog Event Page
     *
     * @var CatalogEventNew
     */
    protected $catalogEventNew;

    /**
     * Event Page
     *
     * @var CatalogEventIndex
     */
    protected $catalogEventIndex;

    /**
     * @param CatalogEventNew $catalogEventNew
     * @param CatalogEventIndex $catalogEventIndex
     * @param FixtureFactory $fixtureFactory
     *
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
        $product = $fixtureFactory->createByCode(
            'catalogProductSimple',
            ['dataset' => 'product_with_category']
        );
        $product->persist();

        /** @var CategoryIds $sourceCategories */
        $sourceCategory = $product->getDataFieldConfig('category_ids')['source']->getCategories()[0];
        $catalogEventEntity = $fixtureFactory->createByCode(
            'catalogEventEntity',
            [
                'dataset' => 'default',
                'data' => ['category_id' => $sourceCategory->getId()],
            ]
        );
        $catalogEventEntity->persist();

        return [
            'product' => $product,
            'catalogEventEntity' => $catalogEventEntity,
        ];
    }

    /**
     * Delete Catalog Event Entity
     *
     * @param CatalogProductSimple $product
     * @return void
     */
    public function testDeleteCatalogEvent(
        CatalogProductSimple $product
    ) {
        $filter = [
            'category_name' => $product->getCategoryIds()[0],
        ];

        //Steps
        $this->catalogEventIndex->open();
        $this->catalogEventIndex->getEventGrid()->searchAndOpen($filter);
        $this->catalogEventNew->getPageActions()->delete();
        $this->catalogEventNew->getModalBlock()->acceptAlert();
    }
}
