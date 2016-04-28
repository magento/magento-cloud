<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\Solr\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Tax\Model\ClassModel;
use Magento\Tax\Model\Config;
use Magento\Tax\Model\TaxRuleFixtureFactory;

class DataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Search helper
     *
     * @var \Magento\Solr\Helper\Data
     */
    private $helper;

    /**
     * Object Manager
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Array of default tax classes ids
     *
     * Key is class name
     *
     * @var int[]
     */
    private $taxClasses;

    /**
     * Array of default tax rates ids.
     *
     * Key is rate percentage as string.
     *
     * @var int[]
     */
    private $taxRates;

    /**
     * Array of default tax rules ids.
     *
     * Key is rule code.
     *
     * @var int[]
     */
    private $taxRules;

    /**
     * Helps in creating required tax rules.
     *
     * @var TaxRuleFixtureFactory
     */
    private $taxRuleFixtureFactory;

    /** @var \Magento\Framework\App\MutableScopeConfig */
    private $scopeConfig;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $productTaxClassSource = $this->objectManager->create('Magento\Tax\Model\TaxClass\Source\Product');
        $this->helper = $this->objectManager->create('Magento\Solr\Helper\Data',
            ['productTaxClassSource' => $productTaxClassSource]);
        $this->taxRuleFixtureFactory = new TaxRuleFixtureFactory();
        $this->scopeConfig = $this->objectManager->get('Magento\Framework\App\MutableScopeConfig');
    }

    protected function tearDown()
    {
        $this->tearDownDefaultRules();
    }

    /**
     * @param string[] $configs
     * @param bool $expected
     * @param bool $hasDefaultRules
     * @dataProvider getTaxInfluenceDataProvider
     */
    public function testGetTaxInfluence($configs, $expected, $hasDefaultRules = false)
    {
        $this->markTestSkipped('Solr module disabled');
        foreach ($configs as $config) {
            $this->scopeConfig->setValue($config['path'], $config['value'], ScopeInterface::SCOPE_STORE, 'default');
        }

        if ($hasDefaultRules) {
            $this->setUpDefaultRules();
        }

        $this->assertEquals($expected, $this->helper->getTaxInfluence());
    }

    public function getTaxInfluenceDataProvider()
    {
        return [
            [[], false],
            [
                [
                    [
                        'path' => Config::CONFIG_XML_PATH_PRICE_INCLUDES_TAX,
                        'value' => '0',
                    ],
                    [
                        'path' => Config::CONFIG_XML_PATH_PRICE_DISPLAY_TYPE,
                        'value' => Config::DISPLAY_TYPE_INCLUDING_TAX,
                    ],
                ],
                false,
                true,
            ],
            [
                [
                    [
                        'path' => Config::CONFIG_XML_PATH_PRICE_INCLUDES_TAX,
                        'value' => '1',
                    ],
                    [
                        'path' => Config::CONFIG_XML_PATH_PRICE_DISPLAY_TYPE,
                        'value' => Config::DISPLAY_TYPE_INCLUDING_TAX,
                    ],
                ],
                true,
                true,
            ],
            [
                [
                    [
                        'path' => Config::CONFIG_XML_PATH_PRICE_INCLUDES_TAX,
                        'value' => '1',
                    ],
                    [
                        'path' => Config::CONFIG_XML_PATH_PRICE_DISPLAY_TYPE,
                        'value' => Config::DISPLAY_TYPE_INCLUDING_TAX,
                    ],
                ],
                false,
            ],
        ];
    }

    /**
     * Helper function that sets up some default rules
     */
    private function setUpDefaultRules()
    {
        $this->taxClasses = $this->taxRuleFixtureFactory->createTaxClasses([
                ['name' => 'DefaultCustomerClass', 'type' => ClassModel::TAX_CLASS_TYPE_CUSTOMER],
                ['name' => 'DefaultProductClass', 'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT],
                ['name' => 'HigherProductClass', 'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT],
            ]);

        $this->taxRates = $this->taxRuleFixtureFactory->createTaxRates([
                ['percentage' => 7.5, 'country' => 'US', 'region' => 42],
                ['percentage' => 7.5, 'country' => 'US', 'region' => 12], // Default store rate
            ]);

        $higherRates = $this->taxRuleFixtureFactory->createTaxRates([
                ['percentage' => 22, 'country' => 'US', 'region' => 42],
                ['percentage' => 10, 'country' => 'US', 'region' => 12], // Default store rate
            ]);

        $this->taxRules = $this->taxRuleFixtureFactory->createTaxRules([
                [
                    'code' => 'Default Rule',
                    'customer_tax_class_ids' => [$this->taxClasses['DefaultCustomerClass'], 3],
                    'product_tax_class_ids' => [$this->taxClasses['DefaultProductClass']],
                    'tax_rate_ids' => array_values($this->taxRates),
                    'sort_order' => 0,
                    'priority' => 0,
                ],
                [
                    'code' => 'Higher Rate Rule',
                    'customer_tax_class_ids' => [$this->taxClasses['DefaultCustomerClass'], 3],
                    'product_tax_class_ids' => [$this->taxClasses['HigherProductClass']],
                    'tax_rate_ids' => array_values($higherRates),
                    'sort_order' => 0,
                    'priority' => 0,
                ],
            ]);

        // For cleanup
        $this->taxRates = array_merge($this->taxRates, $higherRates);
    }

    /**
     * Helper function that tears down some default rules
     */
    private function tearDownDefaultRules()
    {
        if ($this->taxRules) {
            $this->taxRuleFixtureFactory->deleteTaxRules(array_values($this->taxRules));
        }
        if ($this->taxRates) {
            $this->taxRuleFixtureFactory->deleteTaxRates(array_values($this->taxRates));
        }
        if ($this->taxClasses) {
            $this->taxRuleFixtureFactory->deleteTaxClasses(array_values($this->taxClasses));
        }
    }
}
