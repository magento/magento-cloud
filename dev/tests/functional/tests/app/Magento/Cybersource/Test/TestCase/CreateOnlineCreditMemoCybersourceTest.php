<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Cybersource\Test\TestCase;

use Magento\Mtf\TestCase\Scenario;

/**
 * Preconditions:
 * 1. Create online credit memo on full amount using Cybersource.
 *
 * Steps:
 * 1. Log in to Admin.
 * 2. Open created order.
 * 3. Create credit memo.
 * 4. Perform assertions.
 *
 * @group Cybersource
 * @ZephyrId MAGETWO-39437, MAGETWO-39443
 */
class CreateOnlineCreditMemoCybersourceTest extends Scenario
{
    /* tags */
    const MVP = 'yes';
    const TEST_TYPE = '3rd_party_test_single_flow';
    const SEVERITY = 'S0';
    /* end tags */

    /**
     * Runs test for online credit memo creation for order placed using Cybersource payment method.
     *
     * @return void
     */
    public function test()
    {
        $this->executeScenario();
    }
}
