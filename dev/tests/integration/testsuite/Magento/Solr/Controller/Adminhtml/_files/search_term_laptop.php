<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
$storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');

/** @var \Magento\Search\Model\Query $model */
$model = $objectManager->create('Magento\Search\Model\Query');
$model->setData(
    [
        'query_text' => 'Laptop',
        'is_active' => true,
        'store_id' => $storeManager->getStore()->getId(),
    ]
);
$model->save();
