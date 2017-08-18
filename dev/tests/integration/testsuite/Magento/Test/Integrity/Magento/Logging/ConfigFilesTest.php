<?php
/**
 * Tests that existing logging.xml files are valid to schema individually and merged.
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Test\Integrity\Magento\Logging;

use Magento\Framework\Component\ComponentRegistrar;

class ConfigFilesTest extends \Magento\TestFramework\TestCase\AbstractConfigFiles
{
    /**
     * Returns the reader class name that will be instantiated via ObjectManager
     *
     * @return string reader class name
     */
    protected function _getReaderClassName()
    {
        return \Magento\Logging\Model\Config\Reader::class;
    }

    /**
     * Returns a string that represents the path to the config file, starting in the app directory.
     *
     * Format is glob, so * is allowed.
     *
     * @return string
     */
    protected function _getConfigFilePathGlob()
    {
        return 'etc/logging.xml';
    }

    /**
     * Returns a path to the per file XSD file, relative to the modules directory.
     *
     * @return string
     */
    protected function _getXsdPath()
    {
        return $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, 'Magento_Logging')
            . '/etc/logging_file.xsd';
    }
}
