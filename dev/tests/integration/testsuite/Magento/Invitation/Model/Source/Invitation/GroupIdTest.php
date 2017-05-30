<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Invitation\Model\Source\Invitation;

/**
 * Test class for \Magento\Invitation\Model\Source\Invitation\GroupId
 */
class GroupIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoAppIsolation enabled
     */
    public function testToOptionArray()
    {
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $sourceGroupId = $objectManager->create('Magento\Invitation\Model\Source\Invitation\GroupId');
        $optionArray = $sourceGroupId->toOptionArray();
        $this->assertContains("General", $optionArray);
        $this->assertContains("Wholesale", $optionArray);
        $this->assertContains("Retailer", $optionArray);
    }
}
