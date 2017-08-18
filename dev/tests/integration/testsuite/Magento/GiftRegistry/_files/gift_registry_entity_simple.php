<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

require __DIR__ . '/../../../Magento/Customer/_files/customer_with_website.php';

/** @var \Magento\Framework\ObjectManagerInterface  $objectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\GiftRegistry\Model\Entity $entity */
$entity = $objectManager->create(
    \Magento\GiftRegistry\Model\Entity::class,
    [
        'data' => [
            'type_id' => 1, //birtday from magento_giftregistry_type table
            'customer_id' => $customer->getId(),
            'website_id' => $customer->getWebsiteId(),
            'is_public' => 0,
            'url_key' => 'gift_regidtry_simple_url',
            'title' => 'Gift Registry',
            'is_active' => true,
        ],
    ]
);
$address = $objectManager->create(
    \Magento\Customer\Model\Address::class,
    [
        'data' => [
            'street' => 'some street',
        ],
    ]
);

$entity->importAddress($address);
$entity->save();
