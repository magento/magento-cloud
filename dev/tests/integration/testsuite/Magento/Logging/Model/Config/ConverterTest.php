<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Logging\Model\Config;

class ConverterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \DOMDocument
     */
    protected $_loggingDom;

    /**
     * @var \Magento\Logging\Model\Config\Converter
     */
    protected $_converter;

    public function setUp()
    {
        $this->_loggingDom = new \DOMDocument();
        $this->_loggingDom->load(__DIR__ . '/_files/logging.xml');
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->_converter = $objectManager->get(\Magento\Logging\Model\Config\Converter::class);
    }

    /**
     * @param string $actionName
     * @param array $expectedResult
     * @dataProvider convertDataProvider
     */
    public function testConvert($actionName, $expectedResult)
    {
        $result = $this->_converter->convert($this->_loggingDom);
        $this->assertEquals($expectedResult, $result['logging']['enterprise_checkout']['actions'][$actionName]);
    }

    /**
     * @return array
     */
    public function convertDataProvider()
    {
        return [
            [
                'adminhtml_customersegment_match',
                [
                    'group_name' => 'enterprise_checkout',
                    'action' => 'refresh_data',
                    'controller_action' => 'adminhtml_customersegment_match',
                    'post_dispatch' => 'Enterprise_CustomerSegment_Model_Logging::postDispatchCustomerSegmentMatch'
                ],
            ],
            [
                'customer_index_save',
                [
                    'group_name' => 'enterprise_checkout',
                    'action' => 'save',
                    'controller_action' => 'customer_index_save',
                    'expected_models' => [
                        'Enterprise_CustomerBalance_Model_Balance' => [],
                        '@' => ['extends' => 'merge'],
                    ],
                    'skip_on_back' => ['adminhtml_customerbalance_form', 'customer_index_edit']
                ]
            ]
        ];
    }
}
