<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

global $fixtureBaseDir;

include $fixtureBaseDir . '/Magento/Sales/_files/order.php';

/** @var $rma \Magento\Rma\Model\Rma */
$rma = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Rma\Model\Rma::class);
$rma->setOrderId($order->getId());
$rma->setIncrementId(1);
$rma->save();

$history = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Rma\Model\Rma\Status\History::class
);
$history->setRma($rma);
$history->setRmaEntityId($rma->getId());
$history->saveComment('Test comment', true, true);

/** @var $trackingNumber \Magento\Rma\Model\Shipping */
$trackingNumber = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Rma\Model\Shipping::class
);
$trackingNumber->setRmaEntityId($rma->getId())->setCarrierTitle('CarrierTitle')->setTrackNumber('TrackNumber');
$trackingNumber->save();
