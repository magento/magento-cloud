<?php
/*
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var \Magento\SalesRule\Model\Rule $salesRuleFactory */
$salesRuleFactory =
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\SalesRule\Model\RuleFactory');
for ($i = 0; $i < 3; $i++) {
    $ruleName = sprintf('Matching Customer %1$d', $i + 1);
    $salesRule->setData(
        [
            'name'                  => $ruleName,
            'is_active'             => 1,
            'customer_group_ids'    => [
                0 => '0',
                1 => '1',
                2 => '2',
                3 => '3',
            ],
            'coupon_type'           => \Magento\SalesRule\Model\Rule::COUPON_TYPE_NO_COUPON,
            'conditions'            => [
                [
                    'type'     => 'Magento\\CustomerSegment\\Model\\Segment\\Condition\\Segment',
                    'operator' => '==',
                    'value'    => $i + 1,
                ]
            ],
            'simple_action'         => 'by_fixed',
            'discount_amount'       => 1,
            'stop_rules_processing' => 1,
            'website_ids'           => [
                \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
                    'Magento\Store\Model\StoreManagerInterface'
                )->getWebsite()->getId()
            ]
        ]
    );
    $salesRule = $salesRuleFactory->create();
    $salesRule->save();
}
