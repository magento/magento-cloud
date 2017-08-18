<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerSegment\Model\ResourceModel;

class SegmentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createConditionSqlDataProvider
     */
    public function testCreateConditionSql($field, $operator, $value, $expected)
    {
        $segment = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerSegment\Model\ResourceModel\Segment::class
        );
        $result = $segment->createConditionSql($field, $operator, $value);
        $this->assertEquals($expected, $result);
    }

    /**
     * @see self::testCreateConditionSql()
     * @return array
     */
    public function createConditionSqlDataProvider()
    {
        return [
            'Operator is' => ['value', '==', '90064', "value = '90064'"],
            'Operator is multiple values' => ['value', '==', '90064,90065', "value IN ('90064', '90065')"],
            'Operator is not' => ['value', '!=', '90064', "value <> '90064'"],
            'Operator is not multiple values' => [
                'value',
                '!=',
                '90064,90065',
                "value NOT IN ('90064', '90065')",
            ],
            'Operator contains' => ['value', '{}', '90064', "value LIKE '%90064%'"],
            'Operator contains multiple values' => [
                'value',
                '{}',
                '90064,90065',
                "value LIKE '%90064%' AND value LIKE '%90065%'",
            ],
            'Operator does not contain' => ['value', '!{}', '90064', "value NOT LIKE '%90064%'"],
            'Operator does not contain multiple values' => [
                'value',
                '!{}',
                '90064,90065',
                "value NOT LIKE '%90064%' AND value NOT LIKE '%90065%'",
            ],
            'Operator contains array' => [
                'value',
                '[]',
                [90064, 90065],
                '(FIND_IN_SET(90064, value) OR FIND_IN_SET(90065, value))>0',
            ],
            'Operator does not contains array' => [
                'value',
                '![]',
                [90064, 90065],
                '(FIND_IN_SET(90064, value) OR FIND_IN_SET(90065, value))=0',
            ],
            'Operator is array' => [
                'value',
                'finset',
                [90064, 90065],
                'FIND_IN_SET(90064, value)>0 AND FIND_IN_SET(90065, value)>0 AND 11=LENGTH(value)',
            ],
            'Operator is not array' => [
                'value',
                '!finset',
                [90064, 90065],
                'FIND_IN_SET(90064, value)=0 OR FIND_IN_SET(90065, value)=0 OR 11<>LENGTH(value)',
            ],
        ];
    }
}
