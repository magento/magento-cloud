<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

require __DIR__ . '/../../../Magento/Customer/_files/customer.php';
require __DIR__ . '/../../../Magento/Customer/_files/quote.php';

$conditions = [
    'conditions' => [
            1 => [
                    'type' => \Magento\Reminder\Model\Rule\Condition\Combine\Root::class,
                    'aggregator' => 'all',
                    'value' => '1',
                    'new_child' => '',
                ],
            '1--1' => [
                    'type' => \Magento\Reminder\Model\Rule\Condition\Cart::class,
                    'operator' => '>',
                    'value' => '',
                    'aggregator' => 'all',
                    'new_child' => '',
                ],
            '1--1--1' => [
                    'type' => \Magento\Reminder\Model\Rule\Condition\Cart\Subselection::class,
                    'operator' => '==',
                    'aggregator' => 'all',
                    'new_child' => '',
                ],
            '1--1--1--1' => [
                    'type' => \Magento\Reminder\Model\Rule\Condition\Cart\Sku::class,
                    'operator' => '==',
                    'value' => 'simple',
                ],
        ],
];
/** @var $rule \Magento\Reminder\Model\Rule */
$rule = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Reminder\Model\Rule::class);
$rule->loadPost($conditions);
$rule->setData(
    [
        'name' => 'Rule 1',
        'description' => 'Rule 1 Desc',
        'conditions_serialized' => json_encode($rule->getConditions()->asArray()),
        'condition_sql' => 1,
        'is_active' => 1,
        'salesrule_id' => null,
        'schedule' => 2,
        'default_label' => null,
        'default_description' => null,
        'from_date' => null,
        'to_date' => null,
        'website_ids' => 1,
    ]
)->save();
