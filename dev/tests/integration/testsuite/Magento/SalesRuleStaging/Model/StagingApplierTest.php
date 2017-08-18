<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesRuleStaging\Model;

/**
 * Class StagingApplierTest to verify Staging Applier with SalesRule update
 */
class StagingApplierTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Verify that Staging applier run without fatal errors for sales rule entity
     *
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/SalesRuleStaging/_files/update.php
     */
    public function testExecute()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var  \Magento\Staging\Model\StagingApplier $stagingApplier */
        $stagingApplier = $objectManager->get(\Magento\Staging\Model\StagingApplier::class);
        $notIndexedEntities = $objectManager->get(\Magento\Staging\Model\ResourceModel\Db\GetNotIndexedEntities::class);
        $salesRuleEntities = $notIndexedEntities->execute(
            \Magento\SalesRule\Api\Data\RuleInterface::class,
            1,
            strtotime('now')
        );
        $this->assertNotEmpty($salesRuleEntities, 'SalesRule Entities for staging applier aren\'t exist.');
        $stagingApplier->execute();
    }
}
