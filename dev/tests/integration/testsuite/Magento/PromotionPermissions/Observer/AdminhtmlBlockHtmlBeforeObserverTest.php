<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\PromotionPermissions\Observer;

/**
 * @magentoAppArea adminhtml
 */
class AdminhtmlBlockHtmlBeforeObserverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout = null;

    /**
     * @var \PHPUnit\Framework\MockObject_MockObject
     */
    protected $_moduleListMock;

    protected function setUp()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $objectManager->get(
            \Magento\Framework\Config\ScopeInterface::class
        )->setCurrentScope(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE
        );
        $this->_layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        );
    }

    /**
     * @dataProvider blockHtmlBeforeDataProvider
     * @magentoAppIsolation enabled
     */
    public function testAdminhtmlBlockHtmlBefore($parentBlock, $childBlock)
    {
        $block = $this->_layout->createBlock(\Magento\Backend\Block\Template::class, $parentBlock);
        $this->_layout->addBlock(\Magento\Backend\Block\Template::class, $childBlock, $parentBlock);
        $gridBlock = $this->_layout->addBlock(
            \Magento\Backend\Block\Template::class,
            'banners_grid_serializer',
            $childBlock
        );

        $this->assertSame($gridBlock, $this->_layout->getChildBlock($childBlock, 'banners_grid_serializer'));
        $event = new \Magento\Framework\Event\Observer();
        $event->setBlock($block);
        $observer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\PromotionPermissions\Observer\AdminhtmlBlockHtmlBeforeObserver::class
        );
        $observer->execute($event);

        $this->assertFalse($this->_layout->getChildBlock($childBlock, 'banners_grid_serializer'));
    }

    /**
     * @return array
     */
    public function blockHtmlBeforeDataProvider()
    {
        return [
            ['promo_quote_edit_tabs', 'salesrule.related.banners'],
            ['promo_catalog_edit_tabs', 'catalogrule.related.banners']
        ];
    }
}
