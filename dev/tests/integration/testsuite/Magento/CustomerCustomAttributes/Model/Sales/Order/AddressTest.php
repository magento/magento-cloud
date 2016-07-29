<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerCustomAttributes\Model\Sales\Order;

/**
 * @magentoDataFixture Magento/CustomerCustomAttributes/_files/order_address_with_attribute.php
 */
class AddressTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\CustomerCustomAttributes\Model\Sales\Order\Address
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\CustomerCustomAttributes\Model\Sales\Order\Address'
        );
    }

    public function testAttachDataToEntities()
    {
        $address = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Sales\Model\Order\Address'
        );
        $address->load('admin@example.com', 'email');

        $entity = new \Magento\Framework\DataObject(['id' => $address->getId()]);
        $this->assertEmpty($entity->getData('fixture_address_attribute'));
        $this->_model->attachDataToEntities([$entity]);
        $this->assertEquals('fixture_attribute_custom_value', $entity->getData('fixture_address_attribute'));
    }
}
