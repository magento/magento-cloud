<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\Framework\Registry;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\WebsiteRestriction\Model\Config;

$objectManager = Bootstrap::getObjectManager();

/** @var Registry $registry */
$registry = $objectManager->get(Registry::class);

$isRestrictionEnable = $registry->registry('is_restriction_enabled_before_test');

if ($isRestrictionEnable !== null) {
    /** @var \Magento\Config\Model\Config $config */
    $config = $objectManager->create(\Magento\Config\Model\Config::class);
    $config->setDataByPath(Config::XML_PATH_RESTRICTION_ENABLED, $isRestrictionEnable);
    $config->save();

    $registry->unregister('is_restriction_enabled_before_test');
}
