<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogRuleStaging\Model;

/**
 * Class CatalogRuleApplierTest verifies indexers invalidation after Catalog Rule update
 */
class CatalogRuleApplierTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Verify Catalog Rule applier invalidates indexers
     *
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/CatalogRuleStaging/_files/catalog_rule_and_update.php
     */
    public function testExecute()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /* @var \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry */
        $indexerRegistry = $objectManager->get(\Magento\Framework\Indexer\IndexerRegistry::class);
        $productRuleIndex = $indexerRegistry->get('catalogrule_rule');
        $catalogProductPriceIndex = $indexerRegistry->get('catalog_product_price');
        $catalogRuleProductIndex = $indexerRegistry->get('catalogrule_product');
        /* @var \Magento\Staging\Model\StagingApplier $stagingApplier */
        $stagingApplier = $objectManager->get(\Magento\Staging\Model\StagingApplier::class);
        $notIndexedEntities = $objectManager->get(
            \Magento\Staging\Model\ResourceModel\Db\GetNotIndexedEntities::class
        );
        $notIndexedEntities->execute(
            \Magento\CatalogRule\Api\Data\RuleInterface::class,
            1,
            strtotime('now')
        );

        $stagingApplier->execute();

        $this->assertTrue(
            $productRuleIndex->isInvalid()
            && $catalogProductPriceIndex->isInvalid()
            && $catalogRuleProductIndex->isInvalid()
        );
    }
}
