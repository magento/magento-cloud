<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var \Magento\GiftCardAccount\Model\ResourceModel\Pool $poolResourceModel */
$poolResourceModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    'Magento\GiftCardAccount\Model\ResourceModel\Pool'
);
// clean up pool
$poolResourceModel->cleanupByStatus(\Magento\GiftCardAccount\Model\Pool\AbstractPool::STATUS_FREE);
$poolResourceModel->cleanupByStatus(\Magento\GiftCardAccount\Model\Pool\AbstractPool::STATUS_USED);
// insert codes to pool
$poolResourceModel->saveCode('fixture_code_1');
$poolResourceModel->saveCode('fixture_code_2');
$poolResourceModel->saveCode('fixture_code_3');

/** @var \Magento\GiftCardAccount\Model\Pool $poolModel */
$poolModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\GiftCardAccount\Model\Pool');
$poolModel->setCode('fixture_code_1')->setStatus(\Magento\GiftCardAccount\Model\Pool\AbstractPool::STATUS_USED);
$poolModel->save();
