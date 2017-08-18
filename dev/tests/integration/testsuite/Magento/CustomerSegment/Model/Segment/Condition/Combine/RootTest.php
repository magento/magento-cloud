<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerSegment\Model\Segment\Condition\Combine;

class RootTest extends \PHPUnit\Framework\TestCase
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
     * @dataProvider prepareConditionsSqlDataProvider
     * @param mixed $customer
     * @param int $website
     * @param array $expected
     */
    public function testPrepareConditionsSql($customer, $website, $expected)
    {
        $testMethod = new \ReflectionMethod($this->_model, '_prepareConditionsSql');
        $testMethod->setAccessible(true);

        $result = $testMethod->invoke($this->_model, $customer, $website);
        foreach ($expected as $part) {
            $this->assertContains($part, (string)$result, '', true);
        }
    }

    public function prepareConditionsSqlDataProvider()
    {
        return [
            [
                null,
                new \Zend_Db_Expr(1),
                ['`root`.`entity_id`', '`root`.`website_id`', 'where (website_id=1)'],
            ],
            [null, 2, ['`root`.`entity_id`', '`root`.`website_id`', 'where (website_id=2)']],
            [1, 3, ['select 1']]
        ];
    }
}
