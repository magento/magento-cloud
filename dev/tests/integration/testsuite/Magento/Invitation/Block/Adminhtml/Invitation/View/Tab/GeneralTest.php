<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Block\Adminhtml\Invitation\View\Tab;

/**
 * Invitation create form
 *
 * @magentoAppArea adminhtml
 */
class GeneralTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoAppIsolation enabled
     */
    public function testPrepareFormForCustomerGroup()
    {
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $objectManager->get(
            'Magento\Framework\View\DesignInterface'
        )->setArea(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE
        )->setDefaultDesignTheme();

        $block = $objectManager->create('Magento\Invitation\Block\Adminhtml\Invitation\View\Tab\General');

        $this->assertContains("General", $block->getCustomerGroupCode(1));
        $this->assertContains("Wholesale", $block->getCustomerGroupCode(2));
        $this->assertContains("Retailer", $block->getCustomerGroupCode(3));
    }
}
