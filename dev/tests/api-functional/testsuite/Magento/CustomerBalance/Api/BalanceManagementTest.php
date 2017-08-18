<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Balance Management test
 */
class BalanceManagementTest extends WebapiAbstract
{
    const SERVICE_VERSION = 'V1';
    const SERVICE_NAME = 'customerBalanceBalanceManagementV1';
    const RESOURCE_PATH = '/V1/carts/mine/balance/';

    public function testApply()
    {
        $this->markTestIncomplete('will be fixed in TD scope');
    }

    public function testApplicate()
    {
        $this->markTestIncomplete('will be fixed in TD scope');
    }
}
