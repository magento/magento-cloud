<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Adds existing catalog rule to banner
 */

require __DIR__ . '/banner.php';
require __DIR__ . '/../../../Magento/CatalogRule/_files/catalog_rule_10_off_not_logged.php';

$catalogRule = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\CatalogRule\Model\Rule');
$ruleId = $catalogRule->getCollection()->getFirstItem()->getId();

$banner = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Banner\Model\Banner');
$banner->load('Test Banner', 'name')->setBannerCatalogRules([$ruleId])->save();
