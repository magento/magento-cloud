<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\Config\Model\Config;
use Magento\Framework\Registry;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/** @var Registry $registry */
$registry = $objectManager->get(Registry::class);

/** @var Config $config */
$config = $objectManager->create(Config::class);

$isRestrictionEnable = (int)$config->getConfigDataValue(
    \Magento\WebsiteRestriction\Model\Config::XML_PATH_RESTRICTION_ENABLED
);

$registry->unregister('is_restriction_enabled_before_test');
$registry->register('is_restriction_enabled_before_test', $isRestrictionEnable);

$config->setDataByPath(\Magento\WebsiteRestriction\Model\Config::XML_PATH_RESTRICTION_ENABLED, 1);
$config->save();
