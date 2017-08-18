<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftRegistry\Model\Attribute;

use Magento\Framework\Phrase;

/**
 * @magentoCache config disabled
 */
class ConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\GiftRegistry\Model\Attribute\Config
     */
    private $config;

    /**
     * @var \Magento\Framework\Phrase\RendererInterface
     */
    private $originalRenderer;

    protected function setUp()
    {
        parent::setUp();
        $this->originalRenderer = Phrase::getRenderer();
        $translateRenderer = $this->createMock(\Magento\Framework\Phrase\RendererInterface::class);
        $translateRenderer->expects($this->any())->method('render')
            ->will(
                $this->returnCallback(
                    function ($input) {
                        return end($input) . ' (translated)';
                    }
                )
            );
        \Magento\Framework\Phrase::setRenderer($translateRenderer);

        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $fileReadFactory = $objectManager->create(\Magento\Framework\Filesystem\File\ReadFactory::class);
        $paths = [
            __DIR__ . '/../Config/_files/Magento/GiftRegistry/etc/giftregistry.xml',
            __DIR__ . '/../Config/_files/Magento/Reward/etc/giftregistry.xml',
        ];
        $fileList = new \Magento\Framework\Config\FileIterator($fileReadFactory, $paths);
        $fileResolverMock = $this->createMock(\Magento\Framework\Config\FileResolverInterface::class);
        $fileResolverMock->method('get')
            ->willReturn($fileList);
        /** @var \Magento\Logging\Model\Config\Reader $reader */
        $reader = $objectManager->create(
            \Magento\GiftRegistry\Model\Config\Reader::class,
            [
                'fileResolver' => $fileResolverMock,
            ]
        );
        $configData = $objectManager->create(
            \Magento\GiftRegistry\Model\Config\Data::class,
            ['reader' => $reader]
        );
        $this->config = $objectManager->create(
            \Magento\GiftRegistry\Model\Attribute\Config::class,
            ['dataContainer' => $configData]
        );
    }

    protected function tearDown()
    {
        Phrase::setRenderer($this->originalRenderer);
    }

    public function testGetAttributeTypesOptions()
    {
        $expected = [
            [
                'label' => __('-- Please select --'),
                'value' => '',
            ],
            [
                'label' => __('Custom Types'),
                'value' => [
                    [
                        'label' => __('Text'),
                        'value' => 'text',
                    ],
                    [
                        'label' => __('Text'),
                        'value' => 'text2',
                    ],
                ]
            ],
            [
                'label' => __('Static Types'),
                'value' => [
                    [
                        'label' => __('Event Country'),
                        'value' => 'country:event_country:event_information',
                    ],
                    [
                        'label' => __('Event Country'),
                        'value' => 'country:event_country2:event_information',
                    ],
                    [
                        'label' => __('Role'),
                        'value' => 'select:role:registrant',
                    ],
                    [
                        'label' => __('Role'),
                        'value' => 'select:role2:registrant',
                    ],
                ]
            ],
        ];
        $result = $this->config->getAttributeTypesOptions();
        $this->assertEquals($expected, $result);
    }

    public function testGetAttributeGroupsOptions()
    {
        $expected = [
            [
                'label' => __('-- Please select --'),
                'value' => '',
            ],
            [
                'label' => __('Event Information'),
                'value' => 'event_information',
            ],
            [
                'label' => __('Event Information'),
                'value' => 'event_information2',
            ],
        ];
        $result = $this->config->getAttributeGroupsOptions();
        $this->assertEquals($expected, $result);
    }

    public function testGetAttributeGroups()
    {
        $config = include __DIR__ . '/../Config/_files/giftregistry_config.php';
        $expected = $config['attribute_groups'];
        $result = $this->config->getAttributeGroups();
        $this->assertEquals($expected, $result);
    }

    public function testGetStaticTypes()
    {
        $config = include __DIR__ . '/../Config/_files/giftregistry_config.php';
        $expected = array_merge(
            $config['registry']['static_attributes'],
            $config['registrant']['static_attributes']
        );
        $result = $this->config->getStaticTypes();
        $this->assertEquals($expected, $result);
    }

    public function testGetStaticTypesCodes()
    {
        $config = include __DIR__ . '/../Config/_files/giftregistry_config.php';
        $expected = array_keys(
            array_merge(
                $config['registry']['static_attributes'],
                $config['registrant']['static_attributes']
            )
        );
        $result = $this->config->getStaticTypesCodes();
        $this->assertEquals($expected, $result);
    }

    public function testIsRegistrantAttribute()
    {
        $this->assertFalse($this->config->isRegistrantAttribute('event_country'));
        $this->assertTrue($this->config->isRegistrantAttribute('role'));
    }
}
