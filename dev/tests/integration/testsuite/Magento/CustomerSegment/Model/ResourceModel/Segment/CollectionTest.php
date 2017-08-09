<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerSegment\Model\ResourceModel\Segment;

use Magento\CustomerSegment\Model\ResourceModel\Segment\Collection;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Customer segment collection test.
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    protected $collection;

    protected function setUp()
    {
        $this->collection = Bootstrap::getObjectManager()->create(Collection::class);
    }

    /**
     * Test afterLoad collection method.
     *
     * @magentoDataFixture Magento/CustomerSegment/_files/segment_default.php
     */
    public function testAfterLoad()
    {
        $this->collection->addFieldToFilter('name', 'Customer Segment Default');

        self::assertEquals(1, $this->collection->count());

        /** @var \Magento\CustomerSegment\Model\Segment $segment */
        $segment = $this->collection->getItemByColumnValue('name', 'Customer Segment Default');

        self::assertNotNull($segment);
        self::assertTrue($segment->hasWebsiteIds());
        self::assertNotEmpty($segment->getWebsiteIds());
        self::assertEquals([1], $segment->getWebsiteIds());
    }
}
