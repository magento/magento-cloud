<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\TestFramework\Helper\Bootstrap;
use Magento\Staging\Api\UpdateRepositoryInterface;
use Magento\Staging\Api\Data\UpdateInterface;

$objectManager = Bootstrap::getObjectManager();

$updateId = strtotime('+5 minutes');
$updates = [
    [
        'name' => 'Update 1',
        'start_time' => date('Y-m-d H:i:s', strtotime('+3 minutes')),
        'moved_to' => $updateId
    ],
    [
        'name' => 'Update 2',
        'start_time' => date('Y-m-d H:i:s', $updateId),
        'end_time' => date('Y-m-d H:i:s', strtotime('+10 minutes'))
    ],
    [
        'name' => 'Rollback in the past',
        'start_time' => date('Y-m-d H:i:s', strtotime('+12 minutes')),
        'is_rollback' => 1
    ]
];

/** @var UpdateRepositoryInterface $updateRepository */
$updateRepository = $objectManager->get(UpdateRepositoryInterface::class);
foreach ($updates as $update) {
    /** @var UpdateInterface $entity */
    $entity = $objectManager->create(UpdateInterface::class, ['data' => $update]);
    $updateRepository->save($entity);
}
