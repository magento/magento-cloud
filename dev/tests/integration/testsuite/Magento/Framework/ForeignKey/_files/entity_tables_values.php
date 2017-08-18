<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Framework\App\ResourceConnection $resource */
$resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection('default');

$connection->insertArray('base_test_entity', ['entity_id', 'reference_col'], [[1, 1]]);
$connection->insertArray('parent_of_test_entity_one', ['entity_id', 'reference_col'], [[1, 1]]);
$connection->insertArray('test_entity_one', ['entity_id', 'reference_col'], [[1, 1]]);
$connection->insertArray('test_entity_two', ['entity_id', 'reference_col'], [[1, 1], [2, 1]]);
$connection->insertArray('test_entity_three', ['entity_id', 'reference_col'], [[1, 1], [2, 1], [3, 2], [4, 2]]);
$connection->insertArray(
    'test_entity_four',
    ['entity_id', 'reference_col'],
    [
        [1, 1],
        [2, 1],
        [3, 2],
        [4, 2],
        [5, 3],
        [6, 3],
        [7, 4],
        [8, 4]
    ]
);
