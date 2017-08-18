<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\TestFramework\ResourceConnections\Db\ConnectionAdapter;

use Magento\Framework\DB;
use Magento\TestFramework\ResourceConnections\Db\Adapter\MysqlProxy;

// @codingStandardsIgnoreStart
class Mysql extends \Magento\ResourceConnections\DB\ConnectionAdapter\Mysql
// @codingStandardsIgnoreEnd
{
    /**
     * {@inheritdoc}
     */
    protected function getDbConnectionClassName()
    {
        return MysqlProxy::class;
    }
}
