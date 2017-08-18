<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Invitation\Block;

class FormTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Invitation\Block\Form
     */
    protected $_block;

    /**
     * Remembered old value of store config
     * @var array
     */
    protected $_rememberedConfig;

    protected function setUp()
    {
        $this->_block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        )->createBlock(
            \Magento\Invitation\Block\Form::class
        );
    }

    /**
     * @param int $num
     * @param int $expected
     *
     * @dataProvider getMaxInvitationsPerSendDataProvider
     */
    public function testGetMaxInvitationsPerSend($num, $expected)
    {
        $this->_changeConfig(\Magento\Invitation\Model\Config::XML_PATH_MAX_INVITATION_AMOUNT_PER_SEND, $num);
        try {
            $this->assertEquals($expected, $this->_block->getMaxInvitationsPerSend());
        } catch (\Exception $e) {
            $this->_restoreConfig();
            throw $e;
        }
        $this->_restoreConfig();
    }

    /**
     * @return array
     */
    public function getMaxInvitationsPerSendDataProvider()
    {
        return [[1, 1], [3, 3], [100, 100], [0, 1]];
    }

    /**
     * Sets new value to store config path, remembers old value
     *
     * @param  $path
     * @param  $value
     * @return \Magento\Invitation\Block\FormTest
     */
    protected function _changeConfig($path, $value)
    {
        $oldValue = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\App\Config\MutableScopeConfigInterface::class
        )->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\App\Config\MutableScopeConfigInterface::class
        )->setValue(
            $path,
            $value,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if (!$this->_rememberedConfig) {
            $this->_rememberedConfig = ['path' => $path, 'old_value' => $oldValue];
        }
        return $this;
    }

    /**
     * Restores previously remembered store config value
     *
     * @return \Magento\Invitation\Block\FormTest
     */
    protected function _restoreConfig()
    {
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\App\Config\MutableScopeConfigInterface::class
        )->setValue(
            $this->_rememberedConfig['path'],
            $this->_rememberedConfig['old_value'],
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $this->_rememberedConfig = null;
        return $this;
    }

    public function testIsInvitationMessageAllowed()
    {
        try {
            $this->_changeConfig(\Magento\Invitation\Model\Config::XML_PATH_USE_INVITATION_MESSAGE, 1);
            $this->assertEquals(true, $this->_block->isInvitationMessageAllowed());

            $this->_changeConfig(\Magento\Invitation\Model\Config::XML_PATH_USE_INVITATION_MESSAGE, 0);
            $this->assertEquals(false, $this->_block->isInvitationMessageAllowed());
        } catch (\Exception $e) {
            $this->_restoreConfig();
            throw $e;
        }
        $this->_restoreConfig();
    }
}
