<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCardAccount\Api;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\TestFramework\Helper\Bootstrap;

class GiftCardAccountRepositoryInterfaceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var GiftCardAccountRepositoryInterface
     */
    private $repository;

    protected function setUp()
    {
        $this->repository = Bootstrap::getObjectManager()->create(GiftCardAccountRepositoryInterface::class);
    }

    /**
     * @magentoDataFixture Magento/GiftCardAccount/_files/giftcardaccounts_for_search.php
     */
    public function testGetList()
    {
        /** @var FilterBuilder $filterBuilder */
        $filterBuilder = Bootstrap::getObjectManager()->create(FilterBuilder::class);

        $filter1 = $filterBuilder->setField('code')
            ->setValue('gift_card_account_2')
            ->create();
        $filter2 = $filterBuilder->setField('code')
            ->setValue('gift_card_account_3')
            ->create();
        $filter3 = $filterBuilder->setField('code')
            ->setValue('gift_card_account_4')
            ->create();
        $filter4 = $filterBuilder->setField('code')
            ->setValue('gift_card_account_5')
            ->create();
        $filter5 = $filterBuilder->setField('balance')
            ->setValue(45)
            ->setConditionType('lt')
            ->create();

        /**@var SortOrderBuilder $sortOrderBuilder */
        $sortOrderBuilder = Bootstrap::getObjectManager()->create(SortOrderBuilder::class);

        /** @var SortOrder $sortOrder */
        $sortOrder = $sortOrderBuilder->setField('balance')->setDirection(SortOrder::SORT_DESC)->create();

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
        $this->assertEquals('gift_card_account_2', $items[0]['code']);
    }
}
