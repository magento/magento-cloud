<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Payment\Test\Fixture;

use Magento\Mtf\Factory\Factory;
use Magento\Mtf\Fixture\DataFixture;

/**
 * Class Method
 * Shipping methods
 *
 */
class Method extends DataFixture
{
    /**
     * Get payment code
     *
     * @return string
     */
    public function getPaymentCode()
    {
        return $this->getData('fields/payment_code');
    }

    /**
     * Get payment action
     *
     * @return null|string
     */
    public function getPaymentAction()
    {
        return $this->getData('fields/payment_action');
    }

    /**
     * {inheritdoc}
     */
    protected function _initData()
    {
        $this->_repository = Factory::getRepositoryFactory()
            ->getMagentoPaymentMethod($this->_dataConfig, $this->_data);

        //Default data set
        $this->switchData('authorizenet');
    }
}
