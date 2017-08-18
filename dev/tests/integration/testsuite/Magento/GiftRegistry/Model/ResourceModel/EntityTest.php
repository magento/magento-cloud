<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Model\ResourceModel;

use Magento\GiftRegistry\Model\Entity;

/**
 * Entity test class.
 */
class EntityTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    private $objectManager;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/GiftRegistry/_files/gift_registry_entity_simple.php
     */
    public function testExportAddress()
    {
        /** @var Entity $entity */
        $entity = $this->objectManager->get(Entity::class);
        $entity->loadByUrlKey('gift_regidtry_simple_url');
        $address = $entity->exportAddress();
        $this->assertEquals('some street', $address->getData('street'));
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/GiftRegistry/_files/gift_registry_entity_simple.php
     */
    public function testExportAddressData()
    {
        /** @var Entity $entity */
        $entity = $this->objectManager->get(Entity::class);
        $entity->loadByUrlKey('gift_regidtry_simple_url');
        $address = $entity->exportAddressData();
        $this->assertEquals('some street', $address->getStreet());
    }
}
