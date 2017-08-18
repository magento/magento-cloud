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

/** @var $entityType \Magento\Eav\Model\Entity\Type */
$entityType = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Eav\Model\Config::class
)->getEntityType(
    'customer_address'
);

/** @var $attributeSet \Magento\Eav\Model\Entity\Attribute\Set */
$attributeSet = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Eav\Model\Entity\Attribute\Set::class
);

$attribute = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Attribute::class,
    [
        'data' => [
            'frontend_input' => 'multiselect',
            'frontend_label' => ['multi_select_attribute'],
            'sort_order' => '0',
            'backend_type' => 'varchar',
            'is_user_defined' => 1,
            'is_system' => 0,
            'is_required' => '0',
            'is_visible' => '0',
            'option' => [
                'value' => ['option_0' => ['Option 1'], 'option_1' => ['Option 2']],
                'order' => ['option_0' => 1, 'option_1' => 2],
            ],
            'attribute_set_id' => $entityType->getDefaultAttributeSetId(),
            'attribute_group_id' => $attributeSet->getDefaultGroupId($entityType->getDefaultAttributeSetId()),
            'entity_type_id' => $entityType->getId(),
            'default_value' => '',
            'source_model' => Magento\Eav\Model\Entity\Attribute\Source\Table::class,
        ]
    ]
);
$attribute->setAttributeCode('multi_select_attribute_code');
$attribute->save();
$connection->beginTransparentTransaction();
