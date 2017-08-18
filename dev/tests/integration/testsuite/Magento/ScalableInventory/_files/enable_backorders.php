<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\TestFramework\Helper\Bootstrap;

$config = [
    'cataloginventory/item_options/backorders' => 1,
    'cataloginventory/item_options/use_deferred_stock_update' => 1,
];
foreach ($config as $path => $configValue) {
    /** @var Value $value */
    $value = Bootstrap::getObjectManager()->create(Value::class);
    $value->setPath($path);
    $value->setValue($configValue);
    $value->save();
}

/** @var ReinitableConfigInterface $reinitableConfig */
$reinitableConfig = Bootstrap::getObjectManager()->get(ReinitableConfigInterface::class);
$reinitableConfig->reinit();
