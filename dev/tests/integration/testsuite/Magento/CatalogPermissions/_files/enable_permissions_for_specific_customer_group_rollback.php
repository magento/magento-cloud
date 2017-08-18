<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\TestFramework\Helper\Bootstrap;

/** @var Value $configValue */
$configValue = Bootstrap::getObjectManager()->create(Value::class);
$configValue->setPath('catalog/magento_catalogpermissions/enabled');
$configValue->setValue(0);
$configValue->save();

/** @var ReinitableConfigInterface $reinitableConfig */
$reinitableConfig = Bootstrap::getObjectManager()->get(ReinitableConfigInterface::class);
$reinitableConfig->reinit();
