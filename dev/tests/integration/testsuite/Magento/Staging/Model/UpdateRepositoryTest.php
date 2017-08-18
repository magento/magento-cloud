<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Staging\Model;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class UpdateRepositoryTest
 * @package Magento\Staging\Model
 * @magentoDbIsolation enabled
 */
class UpdateRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /** @var  UpdateRepository */
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
        $this->repository = $objectManager->create(UpdateRepository::class);
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
     * @magentoDataFixture Magento/Staging/_files/staging_update.php
     */
    public function testGetListWithMultipleFiltersAndSorting()
    {
        /**
         * @var $resourceModel \Magento\SalesRule\Model\ResourceModel\Rule
         */
        $resourceModel = Bootstrap::getObjectManager()->create(\Magento\Staging\Model\ResourceModel\Update::class);
        $entityIdField = $resourceModel->getIdFieldName();

        $filter1 = $this->filterBuilder
            ->setField('name')
            ->setValue('%Permanent%')
            ->setConditionType('nlike')
            ->create();
        $filter2 = $this->filterBuilder
            ->setField($entityIdField)
            ->setConditionType('eq')
            ->setValue(300)
            ->create();
        $filter3 = $this->filterBuilder
            ->setField($entityIdField)
            ->setConditionType('eq')
            ->setValue(500)
            ->create();
        $filter4 = $this->filterBuilder
            ->setField('rollback_id')
            ->setConditionType(600)
            ->setValue(500)
            ->create();
        $sortOrder = $this->sortOrderBuilder
            ->setField($entityIdField)
            ->setDirection('DESC')
            ->create();

        $this->searchCriteriaBuilder->addFilters([$filter1, $filter4]);
        $this->searchCriteriaBuilder->addFilters([$filter2, $filter3]);
        $this->searchCriteriaBuilder->addSortOrder($sortOrder);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        /** @var \Magento\Framework\Api\SearchResultsInterface $result */
        $result = $this->repository->getList($searchCriteria);
        $items = $result->getItems();
        $this->assertCount(2, $items);
        $this->assertEquals(500, array_shift($items)->getId());
        $this->assertEquals(300, array_shift($items)->getId());
    }
}
