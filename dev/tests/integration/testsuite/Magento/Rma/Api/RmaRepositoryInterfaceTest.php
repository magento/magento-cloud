<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Rma\Api;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Rma\Model\Rma;
use Magento\TestFramework\Helper\Bootstrap;

class RmaRepositoryInterfaceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RmaRepositoryInterface
     */
    private $repository;

    protected function setUp()
    {
        $this->repository = Bootstrap::getObjectManager()->create(RmaRepositoryInterface::class);
    }

    /**
     * @magentoDataFixture Magento/Rma/_files/rmas_for_search.php
     */
    public function testGetList()
    {
        /** @var FilterBuilder $filterBuilder */
        $filterBuilder = Bootstrap::getObjectManager()->create(FilterBuilder::class);

        $filter1 = $filterBuilder->setField(Rma::STATUS)
            ->setValue('status 2')
            ->create();
        $filter2 = $filterBuilder->setField(Rma::STATUS)
            ->setValue('status 3')
            ->create();
        $filter3 = $filterBuilder->setField(Rma::STATUS)
            ->setValue('status 4')
            ->create();
        $filter4 = $filterBuilder->setField(Rma::STATUS)
            ->setValue('status 5')
            ->create();
        $filter5 = $filterBuilder->setField(Rma::CUSTOMER_CUSTOM_EMAIL)
            ->setValue('custom1@custom.net')
            ->create();

        /**@var SortOrderBuilder $sortOrderBuilder */
        $sortOrderBuilder = Bootstrap::getObjectManager()->create(SortOrderBuilder::class);

        /** @var SortOrder $sortOrder */
        $sortOrder = $sortOrderBuilder->setField(Rma::INCREMENT_ID)->setDirection(SortOrder::SORT_DESC)->create();

        /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
        $searchCriteriaBuilder =  Bootstrap::getObjectManager()->create(SearchCriteriaBuilder::class);

        $searchCriteriaBuilder->addFilters([$filter1, $filter2, $filter3, $filter4]);
        $searchCriteriaBuilder->addFilters([$filter5]);
        $searchCriteriaBuilder->setSortOrders([$sortOrder]);

        $searchCriteriaBuilder->setPageSize(2);
        $searchCriteriaBuilder->setCurrentPage(2);

        $searchCriteria = $searchCriteriaBuilder->create();

        $searchResult = $this->repository->getList($searchCriteria);

        $items = array_values($searchResult->getItems());
        $this->assertEquals(1, count($items));
        $this->assertEquals('status 3', $items[0][Rma::STATUS]);
    }
}
