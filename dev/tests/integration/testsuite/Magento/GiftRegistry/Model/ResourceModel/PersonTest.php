<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Model\ResourceModel;

use Magento\GiftRegistry\Model\Person;

/**
 * Person test class.
 */
class PersonTest extends \PHPUnit\Framework\TestCase
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
     * @magentoDataFixture Magento/GiftRegistry/_files/gift_registry_person_simple.php
     */
    public function testCustomAttributesSerialization()
    {
        /** @var Person $person*/
        $person = $this->objectManager->get(Person::class);
        $person->load('fist.last@magento.com', 'email');
        $expectedCustomValues = ['key' => 'value'];
        $this->assertEquals($expectedCustomValues, $person->getCustom());
    }
}
