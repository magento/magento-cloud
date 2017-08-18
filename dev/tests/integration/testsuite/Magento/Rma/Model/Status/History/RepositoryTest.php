<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Rma\Model\Status\History;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Rma\Model\Rma\Status\HistoryRepository;

class RepositoryTest extends \PHPUnit\Framework\TestCase
{
    /** @var  HistoryRepository */
    private $repository;

    /** @var  SortOrderBuilder */
    private $sortOrderBuilder;

    /** @var FilterBuilder */
    private $filterBuilder;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    public function setUp()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->repository = $objectManager->create(HistoryRepository::class);
        $this->searchCriteriaBuilder = $objectManager->create(
            \Magento\Framework\Api\SearchCriteriaBuilder::class
        );
        $this->filterBuilder = $objectManager->get(
            \Magento\Framework\Api\FilterBuilder::class
        );
        $this->sortOrderBuilder = $objectManager->get(
            \Magento\Framework\Api\SortOrderBuilder::class
        );
    }

    /**
     * @magentoDataFixture Magento/Rma/_files/rmas_history_status_for_search.php
     */
    public function testGetListWithMultipleFiltersAndSorting()
    {
        $filter1 = $this->filterBuilder
            ->setField('is_customer_notified')
            ->setValue(1)
            ->create();
        $filter2 = $this->filterBuilder
            ->setField('is_admin')
            ->setValue(1)
            ->create();
        $filter3 = $this->filterBuilder
            ->setField('status')
            ->setValue(0)
            ->create();
        $sortOrder = $this->sortOrderBuilder
            ->setField('entity_id')
            ->setDirection('ASC')
            ->create();

        $this->searchCriteriaBuilder->addFilters([$filter1, $filter2]);
        $this->searchCriteriaBuilder->addFilters([$filter3]);
        $this->searchCriteriaBuilder->addSortOrder($sortOrder);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $result = $this->repository->find($searchCriteria);
        $this->assertCount(2, $result);
        $this->assertEquals('The first', array_shift($result)->getComment());
        $this->assertEquals('The last', array_shift($result)->getComment());
    }
}
