<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GoogleTagManager\Test\TestCase;

use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Configure GTM functionality
 *
 * Steps:
 * 1. Open frontend home page
 * 2. Perform all assertions.
 *
 * @group Google_Tag_Manager
 * @ZephyrId MAGETWO-39519
 */
class GtmHomePageTest extends Injectable
{
    /* tags */
    const TEST_TYPE = 'acceptance_test';
    /* end tags */

    /**
     * Configuration setting.
     *
     * @var string
     */
    protected $configData;

    /**
     * Configure GTM and check it's code on homepage
     *
     * @param string $configData
     * @return array
     */
    public function test(
        $configData
    ) {
        // Preconditions
        $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $configData]
        )->run();
    }

    /**
     * Clean data after running test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $this->configData, 'rollback' => true]
        )->run();
    }
}
