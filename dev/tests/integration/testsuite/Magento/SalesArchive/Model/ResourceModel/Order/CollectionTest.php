<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SalesArchive\Model\ResourceModel\Order;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSelectCountSql()
    {
        $countSql = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\SalesArchive\Model\ResourceModel\Order\Collection'
        )->getSelectCountSql();
        $this->assertInstanceOf('Magento\Framework\DB\Select', $countSql);
    }
}
