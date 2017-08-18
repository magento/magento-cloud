<?php
/*
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$salesRuleFactory = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
    ->create(\Magento\SalesRule\Model\RuleFactory::class);
/** @var \Magento\SalesRule\Model\Rule $salesRule */
$salesRule = $salesRuleFactory->create();
$salesRule->setData(
    [
        'name' => 'Any logged in customers',
        'is_active' => 1,
        'customer_group_ids' => [
            '0',
            '1',
            '2',
            '3',
        ],
        'coupon_type' => \Magento\SalesRule\Model\Rule::COUPON_TYPE_NO_COUPON,
        'simple_action' => 'by_fixed',
        'discount_amount' => 1,
        'stop_rules_processing' => 0,
        'website_ids' => [
            \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
                \Magento\Store\Model\StoreManagerInterface::class
            )->getWebsite()->getId()
        ],
        'is_advanced' => '1',
        'product_ids' => null,
        'apply_to_shipping' => 0,
        'simple_free_shipping' => 0
    ]
);
$conditions = [
    'type' => 'Magento\SalesRule\Model\Rule\Condition\Combine',
    'aggregator' => 'all',
    'value' => 1,
    'new_child' => '',
    'conditions' => [
            [
                'type' => 'Magento\CustomerSegment\Model\Segment\Condition\Segment',
                'operator' => '==',
                'value' => '1', // apply rule to logged in customers only
            ]
        ]
];
$salesRule->getConditions()->loadArray($conditions);

$salesRule->save();
