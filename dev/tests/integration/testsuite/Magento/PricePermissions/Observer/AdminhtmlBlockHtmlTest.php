<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\PricePermissions\Observer;

/**
 * @magentoAppArea adminhtml
 */
class AdminhtmlBlockHtmlTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Magento\Framework\View\LayoutInterface */
    protected $_layout = null;

    protected function setUp()
    {
        parent::setUp();
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\Config\ScopeInterface::class
        )->setCurrentScope(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE
        );
        $this->_layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        );
    }

    public function testAdminhtmlBlockHtmlBeforeProductOpt()
    {
        $parentBlock = $this->_layout->createBlock(\Magento\Backend\Block\Template::class, 'admin.product.options');
        $optionsBlock = $this->_layout->addBlock(
            \Magento\Backend\Block\Template::class,
            'options_box',
            'admin.product.options'
        );

        $this->_initSession();
        $this->_runAdminhtmlBlockHtmlBefore($parentBlock);

        $this->assertFalse($optionsBlock->getCanEditPrice());
        $this->assertFalse($optionsBlock->getCanReadPrice());
    }

    /**
     * Prepare event and run \Magento\PricePermissions\Observer\AdminhtmlBlockHtmlBeforeObserver::execute and
     * \Magento\PricePermissions\Observer\AdminControllerPredispatchObserver::execute
     *
     * @param \Magento\Framework\View\Element\AbstractBlock $block
     */
    protected function _runAdminhtmlBlockHtmlBefore(\Magento\Framework\View\Element\AbstractBlock $block)
    {
        $event = new \Magento\Framework\Event\Observer();
        $event->setBlock($block);

        $adminControllerPredispatchObserver = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\PricePermissions\Observer\AdminControllerPredispatchObserver::class
        );
        $adminControllerPredispatchObserver->execute($event);

        $adminhtmlBlockHtmlBeforeObserver = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\PricePermissions\Observer\AdminhtmlBlockHtmlBeforeObserver::class
        );
        $adminhtmlBlockHtmlBeforeObserver->execute($event);
    }

    /**
     * Prepare session
     */
    protected function _initSession()
    {
        $user = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\User\Model\User::class);
        $user->setId(2)->setRole(true);
        $session = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Backend\Model\Auth\Session::class
        );
        $session->setUpdatedAt(time())->setUser($user);
    }
}
