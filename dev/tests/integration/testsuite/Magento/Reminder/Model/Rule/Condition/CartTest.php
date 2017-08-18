<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Reminder\Model\Rule\Condition;

class CartTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Reminder\Model\Rule\Condition\Cart
     */
    protected $_model;

    /**
     * @dataProvider daysDiffConditionDataProvider
     */
    public function testDaysDiffCondition($operator, $value, $expectedResult)
    {
        $dateModelMock = $this->createPartialMock(\Magento\Framework\Stdlib\DateTime\DateTime::class, ['gmtDate']);
        $dateModelMock->expects($this->atLeastOnce())->method('gmtDate')->will($this->returnValue('2013-12-24'));

        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Reminder\Model\Rule\Condition\Cart::class,
            ['dateModel' => $dateModelMock]
        );
        $this->_model->setOperator($operator);
        $this->_model->setValue($value);

        $where = $this->_model->getConditionsSql(0, 0)->getPart('where');
        $this->assertContains($expectedResult, $where[2]);
    }

    /**
     * @return array
     */
    public function daysDiffConditionDataProvider()
    {
        $firstClause = 'AND ((TO_DAYS(\'2013-12-24 00:00:00\')';

        return [
            [
                '>=',
                '1',
                $firstClause. ' - TO_DAYS(IF(quote.updated_at = 0, quote.created_at, quote.updated_at))) >= 1)'
            ],
            [
                '>',
                '1',
                $firstClause. ' - TO_DAYS(IF(quote.updated_at = 0, quote.created_at, quote.updated_at))) > 1)'
            ],
            [
                '>=',
                '0',
                $firstClause. ' - TO_DAYS(IF(quote.updated_at = 0, quote.created_at, quote.updated_at))) >= 0)'
            ],
            [
                '>',
                '0',
                $firstClause. ' - TO_DAYS(IF(quote.updated_at = 0, quote.created_at, quote.updated_at))) > 0)'
            ]
        ];
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testDaysDiffConditionException()
    {
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Reminder\Model\Rule\Condition\Cart::class
        );
        $this->_model->setOperator('');
        $this->_model->setValue(-1);
        $this->_model->getConditionsSql(0, 0);
    }
}
