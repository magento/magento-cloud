<?php
/**
 * Same as obsolete_properties.php, but specific to Magento EE
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
return [
    ['_eventData', 'Magento\Logging\Block\Adminhtml\Container'],
    ['_customerSegments', 'Magento\CustomerSegment\Model\Customer'],
    ['_limit', 'Magento\Solr\Model\ResourceModel\Index'],
    ['_amountCache', 'Magento\GiftCard\Block\Catalog\Product\Price'],
    ['_minMaxCache', 'Magento\GiftCard\Block\Catalog\Product\Price'],
    ['_skipFields', 'Magento\Logging\Model\Processor'],
    ['_layoutUpdate', 'Magento\WebsiteRestriction\Controller\Index'],
    ['_importExportConfig', 'Magento\ScheduledImportExport\Model\Scheduled\Operation\Data'],
    ['_importModel', 'Magento\ScheduledImportExport\Model\Scheduled\Operation\Data'],
    ['_coreUrl', 'Magento\FullPageCache\Model\Observer'],
    ['_coreSession', 'Magento\FullPageCache\Model\Observer'],
    ['_application', 'Magento\FullPageCache\Model\Observer'],
    ['_app', 'Magento\Banner\Block\Adminhtml\Banner\Edit\Tab\Content'],
    ['_backendSession', 'Magento\AdvancedCheckout\Block\Adminhtml\Manage\Messages', 'backendSession'],
    ['_coreMessage', 'Magento\AdvancedCheckout\Model\Cart', 'messageFactory'],
    ['_coreConfig', 'Magento\CatalogPermissions\App\Backend\Config', 'coreConfig'],
    ['_scopeConfig', 'Magento\CatalogPermissions\App\Config', 'scopeConfig'],
    ['_scopeConfig', 'Magento\CatalogPermissions\Helper\Data', 'config'],
    ['_customerSession', 'Magento\CatalogPermissions\Helper\Data', 'customerSession'],
    ['_storeIds', 'Magento\CatalogPermissions\Model\ResourceModel\Permission\Index'],
    ['_insertData', 'Magento\CatalogPermissions\Model\ResourceModel\Permission\Index'],
    ['_tableFields', 'Magento\CatalogPermissions\Model\ResourceModel\Permission\Index'],
    [
        '_permissionCache',
        'Magento\CatalogPermissions\Model\ResourceModel\Permission\Index',
        'Magento\CatalogPermissions\Model\Indexer\AbstractAction::indexCategoryPermissions'
    ],
    [
        '_grantsInheritance',
        'Magento\CatalogPermissions\Model\ResourceModel\Permission\Index',
        'Magento\CatalogPermissions\Model\Indexer\AbstractAction::grantsInheritance'
    ],
    ['_storeManager', 'Magento\CatalogPermissions\Model\ResourceModel\Permission\Index', 'storeManager'],
    ['_scopeConfig', 'Magento\CatalogPermissions\Model\ResourceModel\Permission\Index'],
    ['_websiteCollectionFactory', 'Magento\CatalogPermissions\Model\ResourceModel\Permission\Index'],
    ['_groupCollectionFactory', 'Magento\CatalogPermissions\Model\ResourceModel\Permission\Index'],
    ['_catalogPermData', 'Magento\CatalogPermissions\Model\ResourceModel\Permission\Index', 'helper'],
    ['_matchedEntities', 'Magento\CatalogPermissions\Model\Permission\Index'],
    ['_isVisible', 'Magento\CatalogPermissions\Model\Permission\Index'],
    ['_transportBuilder', 'Magento\Rma\Model\Rma'],
    ['_rmaConfig', 'Magento\Rma\Model\Rma'],
    ['_historyFactory', 'Magento\Rma\Model\Rma'],
    ['_inlineTranslation', 'Magento\Rma\Model\Rma'],
    [
        '_searchedEntityIds',
        'Magento\Solr\Model\ResourceModel\Collection',
        'Magento\Solr\Model\ResourceModel\Collection::foundEntityIds'
    ],
    ['indexerFactory', 'Magento\Solr\Model\Observer'],
    ['_coreRegistry', 'Magento\Solr\Model\Observer'],
    ['_engineProvider', 'Magento\Solr\Model\Observer'],
    ['_hierarchyLock', 'Magento\VersionsCms\Block\Adminhtml\Cms\Hierarchy\Edit\Form'],
    ['_websiteTable', 'Magento\Reminder\Model\ResourceModel\Rule'],
];
