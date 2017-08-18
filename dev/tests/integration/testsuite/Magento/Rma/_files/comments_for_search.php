<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\Rma\Model\Rma;
use Magento\Rma\Model\Rma\Status\History;
use Magento\TestFramework\Helper\Bootstrap;

global $fixtureBaseDir;

include $fixtureBaseDir . '/Magento/Sales/_files/order.php';

/** @var $rma Rma */
$rma = Bootstrap::getObjectManager()->create(Rma::class);
$rma->setOrderId($order->getId());
$rma->setIncrementId(1);
$rma->save();

$comments = [
    [
        'comment' => 'comment 1',
        'is_visible_on_front' => 1,
        'is_admin' => 1,
    ],
    [
        'comment' => 'comment 2',
        'is_visible_on_front' => 1,
        'is_admin' => 1,
    ],
    [
        'comment' => 'comment 3',
        'is_visible_on_front' => 1,
        'is_admin' => 1,
    ],
    [
        'comment' => 'comment 4',
        'is_visible_on_front' => 1,
        'is_admin' => 1,
    ],
    [
        'comment' => 'comment 5',
        'is_visible_on_front' => 0,
        'is_admin' => 1,
    ],
];

foreach ($comments as $data) {
    /** @var History $history */
    $history = Bootstrap::getObjectManager()->create(History::class);
    $history->setRma($rma);
    $history->setRmaEntityId($rma->getId());
    $history->saveComment($data['comment'], $data['is_visible_on_front'], $data['is_admin']);
}
