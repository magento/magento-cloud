<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\CatalogEvent\Test\Fixture\CatalogEventEntity;
use Magento\CatalogEvent\Test\Page\Adminhtml\CatalogEventIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check catalog event is present in the "Events" grid.
 */
class AssertCatalogEventInGrid extends AbstractConstraint
{
    /**
     * Catalog Event fixture.
     *
     * @var CatalogEventEntity
     */
    protected $catalogEvent;

    /**
     * Pages where event presented.
     *
     * @var string
     */
    protected $catalogEventPages = '';

    /**
     * Catalog Event fixture from repository.
     *
     * @var CatalogEventEntity
     */
    protected $catalogEventOriginal;

    /**
     * Assert that catalog event is presented in the "Events" grid.
     *
     * @param CatalogEventEntity $catalogEvent
     * @param CatalogProductSimple $product
     * @param CatalogEventIndex $catalogEventIndex
     * @param CatalogEventEntity $catalogEventOriginal
     *
     * @return void
     */
    public function processAssert(
        CatalogEventEntity $catalogEvent,
        CatalogProductSimple $product,
        CatalogEventIndex $catalogEventIndex,
        CatalogEventEntity $catalogEventOriginal = null
    ) {
        $categoryName = $product->getCategoryIds()[0];
        $dateStart = strtotime($catalogEvent->getDateStart());
        $dateEnd = strtotime($catalogEvent->getDateEnd());
        $currentDay = strtotime('now');

        if ($currentDay < $dateStart) {
            $status = 'Upcoming';
        } elseif ($currentDay > $dateEnd) {
            $status = 'Closed';
        } else {
            $status = 'Open';
        }

        $this->catalogEvent = ($catalogEventOriginal !== null)
            ? array_merge($catalogEventOriginal->getData(), $catalogEvent->getData())
            : $catalogEvent->getData();

        if (!empty($this->catalogEvent['sort_order'])) {
            $sortOrder = ($this->catalogEvent['sort_order'] < 0) ? 0 : $this->catalogEvent['sort_order'];
        } else {
            $sortOrder = "";
        }

        $dateStart = strftime("%b %e, %Y", $dateStart);
        $filter['start_on'] = $dateStart;
        $dateEnd = strftime("%b %e, %Y", $dateEnd);
        $filter['end_on'] = $dateEnd;

        $filter = [
            'category_name' => $categoryName,
            'start_on' => $dateStart,
            'end_on' => $dateEnd,
            'status' => $status,
            'countdown_ticker' => $this->prepareDisplayStateForFilter(),
            'sort_order' => $sortOrder,
        ];
        $catalogEventIndex->open();
        $catalogEventIndex->getEventGrid()->search(['category_name' => $filter['category_name']]);
        \PHPUnit_Framework_Assert::assertTrue(
            $catalogEventIndex->getEventGrid()->isRowVisible($filter, false, false),
            "Event on category '$categoryName' with $dateStart - $dateEnd is absent in Events grid."
        );
    }

    /**
     * Method prepare string display state for filter.
     *
     * @return string
     */
    protected function prepareDisplayStateForFilter()
    {
        $event = 'Lister Block';
        $displayStates = [
            'category_page' => 'Category Page',
            'product_page' => 'Product Page',
        ];

        $pageEvents = $this->catalogEvent['display_state'];
        foreach ($pageEvents as $key => $pageEvent) {
            if ($pageEvent === 'Yes') {
                $event .= ', ' . $displayStates[$key];
            }
        }

        return $event;
    }

    /**
     * Text present Catalog Event in Event Grid.
     *
     * @return string
     */
    public function toString()
    {
        return 'Catalog Event is present in Event grid.';
    }
}
