<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Payment\Test\Fixture;

use Magento\Mtf\Factory\Factory;
use Magento\Mtf\Fixture\DataFixture;

/**
 * Class Cc
 * Credit cards for checkout
 *
 */
class Cc extends DataFixture
{
    /**
     * {inheritdoc}
     */
    protected function _initData()
    {
        $this->_repository = Factory::getRepositoryFactory()
            ->getMagentoPaymentCc($this->_dataConfig, $this->_data);

        //Default data set
        $this->switchData('visa_default');
    }

    /**
     * Get Credit Card validation password for 3D Secure
     *
     * @return string
     */
    public function getValidationPassword()
    {
        return $this->getData('validation/password/value');
    }
}
