<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

return [
    \Magento\Framework\App\ResourceConnection\ConnectionAdapterInterface::class =>
        \Magento\TestFramework\ResourceConnections\Db\ConnectionAdapter\Mysql::class,
    \Magento\ResourceConnections\DB\ConnectionAdapter\Mysql::class =>
        \Magento\TestFramework\ResourceConnections\Db\ConnectionAdapter\Mysql::class,
];
