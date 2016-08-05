<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Banner\Block\Adminhtml\Banner\Edit\Tab\Promotions;

/**
 * @magentoDataFixture Magento/CatalogRule/_files/catalog_rule_10_off_not_logged.php
 * @magentoAppArea adminhtml
 */
class CatalogruleTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCollection()
    {
        /** @var \Magento\Banner\Block\Adminhtml\Banner\Edit\Tab\Promotions\Catalogrule $block */
        $block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\LayoutInterface'
        )->createBlock(
            'Magento\Banner\Block\Adminhtml\Banner\Edit\Tab\Promotions\Catalogrule'
        );

        /** @var \Magento\CatalogRule\Model\ResourceModel\Rule\Collection $catalogRuleCollection */
        $catalogRuleCollection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            '\Magento\CatalogRule\Model\ResourceModel\Rule\Collection'
        );

        /** @var \Magento\CatalogRule\Model\Rule $catalogRule */
        $catalogRule = $catalogRuleCollection->load()->getItemByColumnValue('name', 'Test Catalog Rule');

        $this->assertSame([$catalogRule->getId()], $block->getCollection()->getAllIds());
    }
}
