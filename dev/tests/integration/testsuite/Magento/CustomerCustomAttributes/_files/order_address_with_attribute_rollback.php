<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var $address \Magento\Sales\Model\Order\Address */
$address = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Sales\Model\Order\Address');
$address->load('admin@example.com', 'email');
$address->delete();

/** @var $attribute \Magento\Customer\Model\Attribute */
$attribute = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Customer\Model\Attribute');
$attribute->loadByCode('customer_address', 'fixture_address_attribute');
$attribute->delete();
