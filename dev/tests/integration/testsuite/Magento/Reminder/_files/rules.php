<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

$conditions = json_encode([]);

/** @var $rule \Magento\Reminder\Model\Rule */
$rule = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Reminder\Model\Rule::class);
$rule->setData(
    [
        'name' => 'Rule 1',
        'description' => 'Rule 1 Desc',
        'conditions_serialized' => $conditions,
        'condition_sql' => 1,
        'is_active' => 1,
        'salesrule_id' => null,
        'schedule' => null,
        'default_label' => null,
        'default_description' => null,
        'from_date' => null,
        'to_date' => '1981-01-01',
    ]
)->save();

$rule = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Reminder\Model\Rule::class);
$rule->setData(
    [
        'name' => 'Rule 2',
        'description' => 'Rule 2 Desc',
        'conditions_serialized' => $conditions,
        'condition_sql' => 1,
        'is_active' => 1,
        'salesrule_id' => null,
        'schedule' => null,
        'default_label' => null,
        'default_description' => null,
        'from_date' => null,
        /**
         * For some reason any values in columns from_date and to_date are ignored
         * This has to be fixed in scope of MAGE-5166
         *
         * Also make sure that dates will be properly formatted through \Magento\Framework\DB\Adapter\*::formatDate()
         */
        'to_date' => date('Y-m-d', time() + 172800),
    ])->save();
