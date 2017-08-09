<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\TestStep;

use Magento\Catalog\Test\Fixture\CatalogProductAttribute;
use Magento\Config\Test\TestStep\SetupConfigurationStep;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepFactory;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Mtf\Util\Command\Cli\Cache;

/**
 * Make attributes visible for category rules in configuration step.
 */
class MakeAttributesVisibleForCategoryRulesStep implements TestStepInterface
{
    /**
     * Attribute fixtures.
     *
     * @var CatalogProductAttribute[]
     */
    private $attributes;

    /**
     * Factory for creation Test Step.
     *
     * @var TestStepFactory
     */
    private $testStepFactory;

    /**
     * Factory for Fixtures.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Cli command to do operations with cache.
     *
     * @var Cache
     */
    private $cache;

    /**
     * @param TestStepFactory $testStepFactory
     * @param FixtureFactory $fixtureFactory
     * @param Cache $cache
     * @param array $attribute
     * @return void
     */
    public function __construct(
        TestStepFactory $testStepFactory,
        FixtureFactory $fixtureFactory,
        Cache $cache,
        array $attribute
    ) {
        $this->attributes = $attribute;
        $this->testStepFactory = $testStepFactory;
        $this->fixtureFactory = $fixtureFactory;
        $this->cache = $cache;
    }

    /**
     * Make attributes visible for category rules.
     *
     * @return void
     */
    public function run()
    {
        $configData = $this->getConfigData();

        if ($configData) {
            $config = $this->fixtureFactory->createByCode('configData', ['data' => $configData]);
            if ($config->hasData('section')) {
                $config->persist();
            }

            $this->cache->flush();

        }
    }

    /**
     * Rollback configuration.
     *
     * @return void
     */
    public function cleanup()
    {
        $this->testStepFactory->create(
            SetupConfigurationStep::class,
            ['configData' => 'visible_attributes_for_category_rules_default', 'flushCache' => true]
        )->run();
    }

    /**
     * Returns data to set in config.
     *
     * @return array|bool
     */
    private function getConfigData()
    {
        if (count($this->attributes) > 0) {
            $arrayValues = [];
            foreach ($this->attributes as $attribute) {
                $arrayValues[$attribute->getFrontendLabel()] = $attribute->getAttributeCode();
            }

            return [
                'visualmerchandiser/options/smart_attributes' => [
                    'scope' => 'visualmerchandiser',
                    'scope_id' => 1,
                    'value' => $arrayValues
                ]
            ];
        }

        return false;
    }
}
