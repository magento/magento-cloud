<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Staging\Model\Update\Grid;

use Magento\Staging\Model\Update\Source\Status;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class SearchResultTest
 */
class SearchResultTest extends \PHPUnit\Framework\TestCase
{
    public function testAddOldUpdatesFilter()
    {
        $dateTimeFactoryMock = $this->getMockBuilder(\Magento\Framework\Intl\DateTimeFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dateTimeObject = new \DateTime('2001-12-31 05:00:11');
        $time = $dateTimeObject->format('Y-m-d H:i:s');
        $dateTimeFactoryMock->expects($this->once())->method('create')->willReturn($dateTimeObject);

        /**
         * @var $searchResult SearchResult
         */
        $searchResult = Bootstrap::getObjectManager()->create(
            SearchResult::class,
            ['dateTimeFactory' => $dateTimeFactoryMock]
        );
        $this->assertContains(
            "(rollbacks.start_time >= '{$time}') OR (rollbacks.start_time IS NULL)",
            $searchResult->getSelect()->assemble()
        );
    }

    /**
     * @magentoDataFixture Magento/Staging/_files/search_staging_update.php
     */
    public function testStatusColumn()
    {
        /**
         * @var $searchResult SearchResult
         */
        $searchResult = Bootstrap::getObjectManager()->create(
            SearchResult::class
        );
        $searchResult->setOrder('status', SearchResult::SORT_ORDER_DESC);
        $items = array_values($searchResult->getItems());
        $this->assertCount(2, $items);
        $this->assertEquals(100, $items[0]['id']);
        $this->assertEquals(Status::STATUS_UPCOMING, $items[0]['status']);
        $this->assertEquals(1, $items[1]['id']);
        $this->assertEquals(Status::STATUS_ACTIVE, $items[1]['status']);
    }
}
