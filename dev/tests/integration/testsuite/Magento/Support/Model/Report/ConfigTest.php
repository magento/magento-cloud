<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Model\Report;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use Magento\Support\Model\Report\Config;
use Magento\Support\Model\Report\Config\Data;
use Magento\Support\Model\Report\Config\Converter;
use Magento\Framework\Phrase;
use Magento\Framework\Phrase\RendererInterface;

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var RendererInterface
     */
    private $origRenderer;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->origRenderer = Phrase::getRenderer();
        /** @var RendererInterface|PHPUnit\Framework\MockObject_MockObject $rendererMock */
        $rendererMock = $this->createMock(RendererInterface::class);
        $rendererMock->expects($this->any())
            ->method('render')
            ->willReturnCallback(
                function ($input) {
                    return end($input) . ' translated';
                }
            );
        Phrase::setRenderer($rendererMock);
    }

    protected function tearDown()
    {
        Phrase::setRenderer($this->origRenderer);
    }

    public function testToOptionArray()
    {
        $options = [
            'option_2' => [
                'title' => 'Option 2',
                'priority' => 1
            ],
            'option_1' => [
                'title' => 'Option 1',
                'priority' => 0
            ],
            'option_3' => [
                'title' => 'Option 3',
                'priority' => 2
            ]
        ];
        $expected = [
            'option_1' => [
                'title' => 'Option 1 translated',
                'priority' => 0
            ],
            'option_2' => [
                'title' => 'Option 2 translated',
                'priority' => 1
            ],
            'option_3' => [
                'title' => 'Option 3 translated',
                'priority' => 2
            ]
        ];
        /** @var Data|PHPUnit\Framework\MockObject_MockObject $dataContainerMock */
        $dataContainerMock = $this->createMock(Data::class);
        $dataContainerMock->expects($this->once())
            ->method('get')
            ->with(Converter::KEY_GROUPS)
            ->willReturn($options);
        /** @var Config $config */
        $config = $this->objectManager->create(
            Config::class,
            [
                'dataContainer' => $dataContainerMock
            ]
        );
        $config->getGroups();
        $actual = $config->getGroups();
        array_walk(
            $actual,
            function (&$item) {
                $item[Converter::KEY_TITLE] = (string) $item[Converter::KEY_TITLE];
            }
        );
        $this->assertEquals($expected, $actual);
    }
}
