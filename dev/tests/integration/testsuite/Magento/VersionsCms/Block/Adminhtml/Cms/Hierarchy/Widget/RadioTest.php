<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\VersionsCms\Block\Adminhtml\Cms\Hierarchy\Widget;

use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * @magentoAppArea adminhtml
 */
class RadioTest extends \PHPUnit\Framework\TestCase
{
    /** @var LayoutInterface */
    protected $layoutMock;

    /** @var Radio */
    protected $block;

    public function setUp()
    {
        parent::setUp();
        $this->layoutMock = $this->createMock(\Magento\Framework\View\Layout::class);
        $this->block = Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        )->createBlock(
            \Magento\VersionsCms\Block\Adminhtml\Cms\Hierarchy\Widget\Radio::class
        );
    }

    /**
     * @dataProvider getParametersDataProvider
     * @magentoAppIsolation enabled
     * @param array|null $blockOptions
     * @param array|null $widgetOptions
     * @param array $expectedResult
     */
    public function testGetParameters($blockOptions, $widgetOptions, $expectedResult)
    {
        $this->block->setWidgetValues($blockOptions);
        $this->layoutMock->expects(
            $this->any()
        )->method(
            'getBlock'
        )->with(
            'wysiwyg_widget.options'
        )->will(
            $this->returnValue($blockOptions ? $this->block : null)
        );

        if ($widgetOptions) {
            $widgetInstance =
                $this->createPartialMock(\Magento\Widget\Model\Widget\Instance::class, ['getWidgetParameters']);
            $widgetInstance->expects(
                $this->once()
            )->method(
                'getWidgetParameters'
            )->will(
                $this->returnValue($widgetOptions)
            );

            /** @var $objectManager \Magento\TestFramework\ObjectManager */
            $objectManager = Bootstrap::getObjectManager();

            $objectManager->get(\Magento\Framework\Registry::class)->unregister('current_widget_instance');
            $objectManager->get(\Magento\Framework\Registry::class)
                ->register('current_widget_instance', $widgetInstance);
        }

        $this->block->setLayout($this->layoutMock);
        $this->block->getParameters();
        $result = $this->block->getParameters();
        $this->assertEquals($result, $expectedResult);
    }

    /**
     * @see self::testGetParameters()
     * @return array
     */
    public function getParametersDataProvider()
    {
        return [
            [['key' => 'value'], null, ['key' => 'value']],
            [null, ['key' => 'value'], ['key' => 'value']],
            [null, null, []]
        ];
    }
}
