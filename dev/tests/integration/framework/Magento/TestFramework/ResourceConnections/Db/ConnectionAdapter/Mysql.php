<?php

namespace Magento\TestFramework\ResourceConnections\Db\ConnectionAdapter;

use Magento\Framework\DB;
use Magento\TestFramework\ResourceConnections\Db\Adapter\MysqlProxy;

/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
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
