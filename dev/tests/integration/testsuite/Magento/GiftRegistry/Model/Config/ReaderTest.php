<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftRegistry\Model\Config;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Component\ComponentRegistrar;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $backupRegistrar;

    public function setUp()
    {
        $reflection = new \ReflectionClass('Magento\Framework\Component\ComponentRegistrar');
        $paths = $reflection->getProperty('paths');
        $paths->setAccessible(true);
        $this->backupRegistrar = $paths->getValue();
        $paths->setAccessible(false);
    }

    public function tearDown()
    {
        $reflection = new \ReflectionClass('Magento\Framework\Component\ComponentRegistrar');
        $paths = $reflection->getProperty('paths');
        $paths->setAccessible(true);
        $paths->setValue($this->backupRegistrar);
        $paths->setAccessible(false);
    }

    public function testRead()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var \Magento\Framework\Filesystem $filesystem */
        $filesystem = $objectManager->create(
            'Magento\Framework\Filesystem',
            [
                'directoryList' => $objectManager->create(
                    'Magento\Framework\App\Filesystem\DirectoryList',
                    [
                        'root' => BP,
                        'config' => [
                            DirectoryList::CONFIG => [DirectoryList::PATH => __DIR__ . '/_files'],
                        ]
                    ]
                )
            ]
        );

        $urnResolver = new \Magento\Framework\Config\Dom\UrnResolver();
        $schemaLocatorMock = $this->getMock('Magento\GiftRegistry\Model\Config\SchemaLocator', [], [], '', false);
        $schemaLocatorMock->expects($this->once())
            ->method('getSchema')
            ->willReturn(
                $urnResolver->getRealPath('urn:magento:module:Magento_GiftRegistry:etc/giftregistry.xsd')
            );

        $reflection = new \ReflectionClass('Magento\Framework\Component\ComponentRegistrar');
        $paths = $reflection->getProperty('paths');
        $paths->setAccessible(true);
        $paths->setValue(
            [ComponentRegistrar::MODULE => [], ComponentRegistrar::LANGUAGE => [], ComponentRegistrar::LIBRARY => []]
        );
        $paths->setAccessible(false);
        ComponentRegistrar::register(
            ComponentRegistrar::MODULE,
            'Magento_GiftRegistry',
            __DIR__ . '/_files/Magento/GiftRegistry'
        );
        ComponentRegistrar::register(
            ComponentRegistrar::MODULE,
            'Magento_Reward',
            __DIR__ . '/_files/Magento/Reward'
        );

        $moduleDirs = $objectManager->create('Magento\Framework\Module\Dir', ['filesystem' => $filesystem]);

        /** @var \Magento\Framework\Module\Dir\Reader $moduleReader */
        $moduleReader = $objectManager->create(
            'Magento\Framework\Module\Dir\Reader',
            ['moduleDirs' => $moduleDirs, 'filesystem' => $filesystem]
        );

        /** @var \Magento\Framework\App\Config\FileResolver $fileResolver */
        $fileResolver = $objectManager->create(
            'Magento\Framework\App\Config\FileResolver',
            ['moduleReader' => $moduleReader]
        );

        /** @var \Magento\Logging\Model\Config\Reader $model */
        $model = $objectManager->create(
            'Magento\GiftRegistry\Model\Config\Reader',
            [
                'fileResolver' => $fileResolver,
                'schemaLocator' => $schemaLocatorMock
            ]
        );

        $result = $model->read('global');
        $expected = include '_files/giftregistry_config.php';
        $this->assertEquals($expected, $result);
    }
}
