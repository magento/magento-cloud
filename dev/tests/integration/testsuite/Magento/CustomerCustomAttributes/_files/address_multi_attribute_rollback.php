<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var $attribute \Magento\Customer\Model\Attribute */
$attribute = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Attribute::class
);
$attribute->loadByCode('customer_address', 'multi_select_attribute_code');
$attribute->delete();
