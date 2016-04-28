<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Config\Test\Fixture;

use Magento\Mtf\Factory\Factory;
use Magento\Mtf\Fixture\DataFixture;

/**
 * Magento configuration settings.
 */
class Config extends DataFixture
{
    /**
     * Persist configuration to application.
     */
    public function persist()
    {
        Factory::getApp()->magentoConfigApplyConfig($this);
    }

    /**
     * Initialize fixture data.
     */
    protected function _initData()
    {
        $this->_repository = Factory::getRepositoryFactory()
            ->getMagentoConfigConfig($this->_dataConfig, $this->_data);
    }
}
