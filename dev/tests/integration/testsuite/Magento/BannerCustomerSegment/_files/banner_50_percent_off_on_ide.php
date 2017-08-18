<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

require __DIR__ . '/../../../Magento/SalesRule/_files/cart_rule_50_percent_off.php';
require __DIR__ . '/../../../Magento/CustomerSegment/_files/segment_developers.php';

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/** @var Magento\Framework\Registry $registry */
$registry = $objectManager->get(\Magento\Framework\Registry::class);

$ruleId = $registry->registry('Magento/SalesRule/_files/cart_rule_50_percent_off');

/** @var $segment \Magento\CustomerSegment\Model\Segment */
$segment = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerSegment\Model\Segment::class
);
$segment->load('Developers', 'name');

/** @var \Magento\Banner\Model\Banner $banner */
$banner = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Banner\Model\Banner::class);
$banner->setData(
    [
        'name' => 'Get 50% Off on Development IDEs',
        'is_enabled' => \Magento\Banner\Model\Banner::STATUS_ENABLED,
        'types' => []/*Any Banner Type*/,
        'store_contents' => ['<img src="http://example.com/banner_50_percent_off_on_ide.png" />'],
        'banner_sales_rules' => [$ruleId],
        'customer_segment_ids' => [$segment->getId()],
    ]
);
$banner->save();
