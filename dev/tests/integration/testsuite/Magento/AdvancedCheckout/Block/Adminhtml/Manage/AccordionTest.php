<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedCheckout\Block\Adminhtml\Manage;

/**
 * @magentoAppArea adminhtml
 */
class AccordionTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Magento\Framework\View\LayoutInterface */
    protected $_layout = null;

    /** @var \Magento\AdvancedCheckout\Block\Adminhtml\Manage\Accordion */
    protected $_block = null;

    protected function setUp()
    {
        parent::setUp();
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\Config\ScopeInterface'
        )->setCurrentScope(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE
        );
        $this->_layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\LayoutInterface'
        );
        $this->_block = $this->_layout->createBlock('Magento\AdvancedCheckout\Block\Adminhtml\Manage\Accordion');
    }

    protected function tearDown()
    {
        $this->_block = null;
        $this->_layout = null;
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\Config\ScopeInterface'
        )->setCurrentScope(
            null
        );
    }

    public function testToHtml()
    {
        $this->_initAcl();
        $parentName = $this->_block->getNameInLayout();
        $this->_block->setArea('adminhtml');

        // set first child - block
        $title = 'Block 1';
        $url = 'http://content.url.1/';
        $this->_layout->addBlock(
            'Magento\Framework\View\Element\Text',
            'block1',
            $parentName
        )->setHeaderText(
            $title
        )->setData(
            'content_url',
            $url
        );

        // set second child - container
        $containerName = 'container';
        $this->_layout->addContainer($containerName, 'Container', [], $parentName);
        $containerText = 'Block in container';
        $this->_layout->addBlock(
            'Magento\Framework\View\Element\Text',
            'container_block',
            $containerName
        )->setText(
            $containerText
        );

        // set third child - block
        $titleOne = 'Block 2';
        $blockContent = 'Block 2 Text';
        $this->_layout->addBlock(
            'Magento\Framework\View\Element\Text',
            'block2',
            $parentName
        )->setHeaderText(
            $titleOne
        )->setText(
            $blockContent
        );

        $html = $this->_block->toHtml();
        $this->assertContains($title, $html);
        $this->assertContains($url, $html);
        $this->assertNotContains($containerText, $html);
        $this->assertContains($titleOne, $html);
        $this->assertContains($blockContent, $html);
    }

    /**
     * Substitutes real ACL object for mocked one to make it always return TRUE
     */
    protected function _initAcl()
    {
        $user = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\User\Model\User');
        $user->setId(1)->setRole(true);
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Backend\Model\Auth\Session'
        )->setUpdatedAt(
            time()
        )->setUser(
            $user
        );
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Framework\AuthorizationInterface',
            ['data' => ['policy' => new \Magento\Framework\Authorization\Policy\DefaultPolicy()]]
        );
    }
}
