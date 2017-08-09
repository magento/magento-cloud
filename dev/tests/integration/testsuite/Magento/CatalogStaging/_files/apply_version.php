<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var \Magento\Staging\Model\VersionHistoryInterface $versionHistory */
$versionHistory = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
    \Magento\Staging\Model\VersionHistoryInterface::class
);

$versionHistory->setCurrentId(1490772480);
