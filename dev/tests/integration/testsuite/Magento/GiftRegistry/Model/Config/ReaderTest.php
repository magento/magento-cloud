<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftRegistry\Model\Config;

use Magento\Framework\Phrase;

class ReaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\GiftRegistry\Model\Config\Reader
     */
    private $reader;

    /**
     * @var \Magento\Framework\Phrase\RendererInterface
     */
    private $originalRenderer;

    protected function setUp()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $fileReadFactory = $objectManager->create(\Magento\Framework\Filesystem\File\ReadFactory::class);
        $paths = [
            __DIR__ . '/_files/Magento/GiftRegistry/etc/giftregistry.xml',
            __DIR__ . '/_files/Magento/Reward/etc/giftregistry.xml',
        ];
        $fileList = new \Magento\Framework\Config\FileIterator($fileReadFactory, $paths);
        $fileResolverMock = $this->createMock(\Magento\Framework\Config\FileResolverInterface::class);
        $fileResolverMock->method('get')
            ->willReturn($fileList);

        $this->originalRenderer = Phrase::getRenderer();
        $translateRendererMock = $this->createMock(\Magento\Framework\Phrase\RendererInterface::class);
        $translateRendererMock->expects($this->any())->method('render')
            ->will(
                $this->returnCallback(
                    function ($input) {
                        return end($input) . ' (translated)';
                    }
                )
            );
        Phrase::setRenderer($translateRendererMock);

        $this->reader = $objectManager->create(
            \Magento\GiftRegistry\Model\Config\Reader::class,
            [
                'fileResolver' => $fileResolverMock,
            ]
        );
    }

    protected function tearDown()
    {
        Phrase::setRenderer($this->originalRenderer);
    }

    public function testRead()
    {
        $result = $this->reader->read('global');
        $expected = include '_files/giftregistry_config.php';
        $this->assertEquals($expected, $result);
    }
}
