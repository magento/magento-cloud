<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerSegment\Model\ResourceModel;

class SegmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createConditionSqlDataProvider
     */
    public function testCreateConditionSql($field, $operator, $value, $expected)
    {
        $segment = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\CustomerSegment\Model\ResourceModel\Segment'
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
            ]
        ];
    }
}
