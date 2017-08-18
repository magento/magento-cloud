<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
$storeManager = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);

/** @var \Magento\Search\Model\Query $model */
$model = $objectManager->create(\Magento\Search\Model\Query::class);
$model->setData(
    [
        'query_text' => 'Laptop',
        'is_active' => true,
        'store_id' => $storeManager->getStore()->getId(),
    ]
);
$model->save();
