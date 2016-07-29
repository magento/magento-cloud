<?php
/**
 * \Magento\MultipleWishlist\Model\ResourceModel\Item\Report\Collection
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MultipleWishlist\Model\ResourceModel\Item\Report;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\MultipleWishlist\Model\ResourceModel\Item\Report\Collection
     */
    protected $_collection;

    public function setUp()
    {
        $this->_collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\MultipleWishlist\Model\ResourceModel\Item\Report\Collection'
        );
    }

    public function testAddCustomerInfo()
    {
        $joinParts = $this->_collection->getSelect()->getPart(\Magento\Framework\DB\Select::FROM);
        $this->assertArrayHasKey('customer', $joinParts);
    }
}
