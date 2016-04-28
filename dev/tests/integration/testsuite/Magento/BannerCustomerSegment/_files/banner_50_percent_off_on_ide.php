<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

require __DIR__ . '/../../../Magento/SalesRule/_files/cart_rule_50_percent_off.php';
require __DIR__ . '/../../../Magento/CustomerSegment/_files/segment_developers.php';

/** @var \Magento\SalesRule\Model\Rule $rule */
$rule = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\SalesRule\Model\Rule');
$rule->load('50% Off on Large Orders', 'name');

/** @var $segment \Magento\CustomerSegment\Model\Segment */
$segment = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    'Magento\CustomerSegment\Model\Segment'
);
$segment->load('Developers', 'name');

/** @var \Magento\Banner\Model\Banner $banner */
$banner = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Banner\Model\Banner');
$banner->setData(
    [
        'name' => 'Get 50% Off on Development IDEs',
        'is_enabled' => \Magento\Banner\Model\Banner::STATUS_ENABLED,
        'types' => []/*Any Banner Type*/,
        'store_contents' => ['<img src="http://example.com/banner_50_percent_off_on_ide.png" />'],
        'banner_sales_rules' => [$rule->getId()],
        'customer_segment_ids' => [$segment->getId()],
    ]
);
$banner->save();
