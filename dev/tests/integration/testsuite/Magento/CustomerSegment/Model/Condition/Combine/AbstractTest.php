<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerSegment\Model\Condition\Combine;

class AbstractTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\CustomerSegment\Model\Segment\Condition\Combine\Root
     */
    protected $_model;

    /**
     * @var \PHPUnit\Framework\MockObject_MockObject
     */
    protected $_resource;

    /**
     * @var \PHPUnit\Framework\MockObject_MockObject
     */
    protected $_configShare;

    protected function setUp()
    {
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerSegment\Model\Segment\Condition\Combine\Root::class
        );
    }

    /**
     * @dataProvider limitByStoreWebsiteDataProvider
     * @param int $website
     */
    public function testLimitByStoreWebsite($website)
    {
        $expectedWhere = is_numeric($website) ? 'main.store_id IN (?)' : 'store.website_id IN (?)';
        $select = $this->createPartialMock(\Magento\Framework\DB\Select::class, ['join', 'where']);
        if ($website instanceof \Zend_Db_Expr) {
            $select->expects(
                $this->once()
            )->method(
                'join'
            )->with(
                $this->arrayHasKey('store'),
                $this->equalTo('main.store_id=store.store_id'),
                $this->equalTo([])
            )->will(
                $this->returnSelf()
            );
        }
        $select->expects(
            $this->once()
        )->method(
            'where'
        )->with(
            $this->equalTo($expectedWhere),
            $this->equalTo($website)
        )->will(
            $this->returnSelf()
        );

        $testMethod = new \ReflectionMethod($this->_model, '_limitByStoreWebsite');
        $testMethod->setAccessible(true);

        $testMethod->invoke($this->_model, $select, $website, 'main.store_id');
    }

    public function limitByStoreWebsiteDataProvider()
    {
        return [[1], [new \Zend_Db_Expr(1)]];
    }
}
