<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var $eventClosed \Magento\CatalogEvent\Model\Event */
$eventClosed = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CatalogEvent\Model\Event::class
);
$eventClosed->setCategoryId(
    null
)->setDateStart(
    date('Y-m-d H:i:s', strtotime('-1 year'))
)->setDateEnd(
    date('Y-m-d H:i:s', strtotime('-1 month'))
)->setDisplayState(
    \Magento\CatalogEvent\Model\Event::DISPLAY_CATEGORY_PAGE
)->setSortOrder(
    30
)->setImage(
    'default_website.jpg'
)->save();
$eventClosed->setStoreId(1)->setImage('default_store_view.jpg')->save();

/** @var $eventOpen \Magento\CatalogEvent\Model\Event */
$eventOpen = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CatalogEvent\Model\Event::class
);
$eventOpen->setCategoryId(
    1
)->setDateStart(
    date('Y-m-d H:i:s', strtotime('-1 month'))
)->setDateEnd(
    date('Y-m-d H:i:s', strtotime('+1 month'))
)->setDisplayState(
    \Magento\CatalogEvent\Model\Event::DISPLAY_PRODUCT_PAGE
)->setSortOrder(
    20
)->setImage(
    'default_website.jpg'
)->save();

/** @var $eventUpcoming \Magento\CatalogEvent\Model\Event */
$eventUpcoming = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CatalogEvent\Model\Event::class
);
$eventUpcoming->setCategoryId(
    2
)->setDateStart(
    date('Y-m-d H:i:s', strtotime('+1 month'))
)->setDateEnd(
    date('Y-m-d H:i:s', strtotime('+1 year'))
)->setDisplayState(
    \Magento\CatalogEvent\Model\Event::DISPLAY_CATEGORY_PAGE | \Magento\CatalogEvent\Model\Event::DISPLAY_PRODUCT_PAGE
)->setSortOrder(
    10
)->setStoreId(
    1
)->setImage(
    'default_store_view.jpg'
)->save();
