<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Paypal\Test\Fixture;

use Magento\Mtf\Factory\Factory;
use Magento\Mtf\Fixture\DataFixture;

/**
 * Class Customer
 * Paypal buyer account
 *
 */
class Customer extends DataFixture
{
    /**
     * {inheritdoc}
     */
    protected function _initData()
    {
        $this->_repository = Factory::getRepositoryFactory()
            ->getMagentoPaypalCustomer($this->_dataConfig, $this->_data);

        //Default data set
        $this->switchData('customer_US');
    }
}
