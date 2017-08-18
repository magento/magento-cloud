<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Banner\Block\Adminhtml\Banner\Edit\Tab\Promotions;

/**
 * @magentoDataFixture Magento/CatalogRule/_files/catalog_rule_10_off_not_logged.php
 * @magentoAppArea adminhtml
 */
class CatalogruleTest extends \PHPUnit\Framework\TestCase
{
    public function testGetCollection()
    {
        /** @var \Magento\Banner\Block\Adminhtml\Banner\Edit\Tab\Promotions\Catalogrule $block */
        $block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        )->createBlock(
            \Magento\Banner\Block\Adminhtml\Banner\Edit\Tab\Promotions\Catalogrule::class
        );

        /** @var \Magento\CatalogRule\Model\ResourceModel\Rule\Collection $catalogRuleCollection */
        $catalogRuleCollection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CatalogRule\Model\ResourceModel\Rule\Collection::class
        );

        /** @var \Magento\CatalogRule\Model\Rule $catalogRule */
        $catalogRule = $catalogRuleCollection->load()->getItemByColumnValue('name', 'Test Catalog Rule');

        $this->assertSame([$catalogRule->getId()], $block->getCollection()->getAllIds());
    }
}
