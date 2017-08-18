<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\Framework\App\ResourceConnection;
use Magento\TestFramework\Helper\Bootstrap;

require __DIR__ . '/../../Checkout/_files/quote_with_check_payment_rollback.php';

/** @var ResourceConnection $resource */
$resource = Bootstrap::getObjectManager()
    ->get(ResourceConnection::class);

$connection = $resource->getConnection('default');
$connection->delete($resource->getTableName('sequence_product'));

$connection->delete($resource->getTableName('staging_update'), [
    'id' => STAGING_UPDATE_FIXTURE_ID
]);
