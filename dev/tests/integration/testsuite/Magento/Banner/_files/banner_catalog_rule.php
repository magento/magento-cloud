<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Adds existing catalog rule to banner
 */

require __DIR__ . '/banner.php';
require __DIR__ . '/../../../Magento/CatalogRule/_files/catalog_rule_10_off_not_logged.php';

$catalogRule = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CatalogRule\Model\Rule::class
);
$ruleId = $catalogRule->getCollection()->getFirstItem()->getId();

$banner = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Banner\Model\Banner::class);
$banner->load('Test Banner', 'name')->setBannerCatalogRules([$ruleId])->save();
