<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/** @var \Magento\Framework\ObjectManagerInterface $objectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

// create gift registry
$data = [
    'type_id' => 1,
    'title' => 'Test Registry',
    'message' => 'Test Message',
    'is_public' => 1,
    'is_active' => 1,
    'event_country' => 'US',
    'event_date' => date('m/d/y', strtotime('+1 month')),
    'registrant' => [
        ['firstname' => 'TestReg', 'lastname' => 'TestReg last', 'email' => 'test1@test.magento.loc'],
    ],
    'address' => [
        'firstname' => 'test',
        'lastname' => 'test',
        'address' => 'test addr',
        'city' => 'LA',
        'region_id' => 1,
        'postcode' => 123456,
        'country_id' => 'US',
        'telephone' => '123456789',
    ],
];
/** @var \Magento\Customer\Model\Customer $customer */
$customer = $objectManager->create(\Magento\Customer\Model\Customer::class);
$customer->load(1);

/** @var \Magento\GiftRegistry\Model\Entity $giftRegistry */
$giftRegistry = $objectManager->create(\Magento\GiftRegistry\Model\Entity::class);
$giftRegistry->setTypeId($data['type_id']);
$giftRegistry->importData(
    $data,
    false
)->setCustomerId(
    $customer->getId()
)->setWebsiteId(
    1
)->setIsAddAction(
    true
)->setUrlKey(
    $giftRegistry->getGenerateKeyId()
);

/** @var \Magento\GiftRegistry\Model\Person $person */
$person = $objectManager->create(\Magento\GiftRegistry\Model\Person::class);
$person->addData($data['registrant'][0]);

/** @var \Magento\Customer\Model\Address $address */
$address = $objectManager->create(\Magento\Customer\Model\Address::class);
$address->isObjectNew(true);
$address->addData($data['address']);
$giftRegistry->importAddress($address);

$giftRegistry->save();
$person->setEntityId($giftRegistry->getId())->save();

$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
$product->load(1);

/** @var \Magento\GiftRegistry\Model\Item $item */
$item = $objectManager->create(\Magento\GiftRegistry\Model\Item::class);
$item->setEntityId($giftRegistry->getId())->setProductId($product->getId())->setQty(2)->save();

$objectManager->get(\Magento\Framework\Registry::class)->register('test_gift_registry', $giftRegistry);
$objectManager->get(\Magento\Framework\Registry::class)->register('test_product', $product);
