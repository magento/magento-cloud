<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Rma\Model;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class RmaRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /** @var  RmaRepository */
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
        $this->repository = $objectManager->create(RmaRepository::class);
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
     * @magentoDataFixture Magento/Rma/_files/rmas_for_search.php
     */
    public function testGetListWithMultipleFiltersAndSorting()
    {
        $filter1 = $this->filterBuilder
            ->setField('status')
            ->setValue('status 3')
            ->create();
        $filter2 = $this->filterBuilder
            ->setField('status')
            ->setValue('status 2')
            ->create();
        $filter3 = $this->filterBuilder
            ->setField('status')
            ->setValue('status 5')
            ->create();
        $filter4 = $this->filterBuilder
            ->setField('customer_custom_email')
            ->setValue('custom1@custom.net')
            ->create();
        $sortOrder = $this->sortOrderBuilder
            ->setField('increment_id')
            ->setDirection('ASC')
            ->create();

        $this->searchCriteriaBuilder->addFilters([$filter1, $filter2, $filter3]);
        $this->searchCriteriaBuilder->addFilters([$filter4]);
        $this->searchCriteriaBuilder->addSortOrder($sortOrder);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $result = $this->repository->find($searchCriteria);
        $this->assertCount(2, $result);
        $this->assertEquals('status 3', array_shift($result)->getStatus());
        $this->assertEquals('status 2', array_shift($result)->getStatus());
    }
}
