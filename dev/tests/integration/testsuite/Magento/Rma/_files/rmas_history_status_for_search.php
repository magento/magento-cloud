<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\TestFramework\Helper\Bootstrap;

/** @var $rma \Magento\Rma\Model\Rma */
$rma = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Rma\Model\Rma::class);
$rma->setIncrementId(1);
$rma->save();

$rmasHistory = [
    [
        'rma_entity_id' => $rma->getId(),
        'is_customer_notified' => 1,
        'is_visible_on_front' => 1,
        'is_admin' => 0,
        'status' => 0,
        'comment' => 'The first'
    ],
    [
        'rma_entity_id' => $rma->getId(),
        'is_customer_notified' => 1,
        'is_visible_on_front' => 1,
        'is_admin' => 0,
        'status' => 1
    ],
    [
        'rma_entity_id' => $rma->getId(),
        'is_customer_notified' => 0,
        'is_visible_on_front' => 1,
        'is_admin' => 0,
        'status' => 0
    ],
    [
        'rma_entity_id' => $rma->getId(),
        'is_customer_notified' => 0,
        'is_visible_on_front' => 0,
        'is_admin' => 1,
        'status' => 0,
        'comment' => 'The last'
    ],
];

foreach ($rmasHistory as $data) {
    /** @var $history \Magento\Rma\Model\Rma\Status\History */
    $history = Bootstrap::getObjectManager()->create(\Magento\Rma\Model\Rma\Status\History::class);
    $history->setData($data);
    $history->save();
}
