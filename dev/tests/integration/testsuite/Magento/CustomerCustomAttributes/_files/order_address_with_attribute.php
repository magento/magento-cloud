<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * This fixture is run outside of the transaction because it performs DDL operations during creating custom attribute.
 * All the changes are reverted in the appropriate "rollback" fixture.
 */

/** @var $connection \Magento\TestFramework\Db\Adapter\TransactionInterface */
$connection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
    \Magento\Framework\App\ResourceConnection::class
)->getConnection(
    'default'
);
$connection->commitTransparentTransaction();

$entityType = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Eav\Model\Config::class
)->getEntityType(
    'customer_address'
);
/** @var $entityType \Magento\Eav\Model\Entity\Type */

$attributeSet = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Eav\Model\Entity\Attribute\Set::class
);
/** @var $attributeSet \Magento\Eav\Model\Entity\Attribute\Set */

$attribute = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Attribute::class,
    [
        'data' => [
            'frontend_input' => 'text',
            'frontend_label' => ['Fixture Customer Address Attribute'],
            'sort_order' => '0',
            'backend_type' => 'varchar',
            'is_user_defined' => 1,
            'is_system' => 0,
            'is_required' => '0',
            'is_visible' => '0',
            'attribute_set_id' => $entityType->getDefaultAttributeSetId(),
            'attribute_group_id' => $attributeSet->getDefaultGroupId($entityType->getDefaultAttributeSetId()),
            'entity_type_id' => $entityType->getId(),
            'default_value' => 'fixture_attribute_default_value',
        ]
    ]
);
$attribute->setAttributeCode('fixture_address_attribute');
$attribute->save();

$addressData = include __DIR__ . '/../../../Magento/Sales/_files/address_data.php';
$billingAddress = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Sales\Model\Order\Address::class,
    ['data' => $addressData]
);
$billingAddress->setAddressType('billing');
$billingAddress->setData($attribute->getAttributeCode(), 'fixture_attribute_custom_value');
$billingAddress->save();

$connection->beginTransparentTransaction();
