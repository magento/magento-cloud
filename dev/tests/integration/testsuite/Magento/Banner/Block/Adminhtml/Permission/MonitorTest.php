<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Banner\Block\Adminhtml\Permission;

/**
 * @magentoAppArea adminhtml
 */
class MonitorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoAppIsolation enabled
     * @param string $blockType
     * @param string $blockName
     * @param string $tabsType
     * @param string $tabsName
     * @dataProvider prepareLayoutDataProvider
     */
    public function testPrepareLayout($blockType, $blockName, $tabsType, $tabsName)
    {
        $layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\LayoutInterface'
        );
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\Config\ScopeInterface'
        )->setCurrentScope(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE
        );
        $layout->addBlock($blockType, $blockName);
        $tabs = $layout->addBlock($tabsType, $tabsName);
        $tab = $layout->addBlock(
            'Magento\Banner\Block\Adminhtml\Promo\Catalogrule\Edit\Tab\Banners',
            'banners_section',
            $tabsName
        );
        $tabs->addTab('banners_section', $tab);

        $this->assertContains('banners_section', $tabs->getTabsIds());
        $this->assertTrue($layout->hasElement($blockName));
        $this->assertInstanceOf($blockType, $layout->getBlock($blockName));
        $layout->createBlock('Magento\Banner\Block\Adminhtml\Permission\Monitor', 'bannner.permission.monitor');
        $this->assertFalse($layout->hasElement($blockName));
        $this->assertFalse($layout->getBlock($blockName));
        $this->assertNotContains('banners_section', $tabs->getTabsIds());
    }

    /**
     * @return array
     */
    public function prepareLayoutDataProvider()
    {
        return [
            [
                'Magento\Banner\Block\Adminhtml\Promo\Salesrule\Edit\Tab\Banners',
                'salesrule.related.banners',
                'Magento\SalesRule\Block\Adminhtml\Promo\Quote\Edit\Tabs',
                'promo_quote_edit_tabs',
            ],
            [
                'Magento\Banner\Block\Adminhtml\Promo\Salesrule\Edit\Tab\Banners',
                'catalogrule.related.banners',
                'Magento\Backend\Block\Widget\Tabs',
                'promo_catalog_edit_tabs'
            ]
        ];
    }
}
