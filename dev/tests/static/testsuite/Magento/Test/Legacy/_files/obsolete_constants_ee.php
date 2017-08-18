<?php
/**
 * Same as obsolete_constants.php, but specific to Magento EE
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
return [
    ['LAST_PRODUCT_COOKIE', 'Magento\FullPageCache\Model\Processor'],
    [
        'NO_CACHE_COOKIE',
        'Magento\FullPageCache\Model\Processor',
        'Magento\FullPageCache\Model\Processor\RestrictionInterface::NO_CACHE_COOKIE'
    ],
    ['XML_PATH_DEFAULT_TIMEZONE', 'Magento\CatalogEvent\Model\Event'],
    [
        'METADATA_CACHE_SUFFIX',
        'Magento\FullPageCache\Model\Processor',
        'Magento\FullPageCache\Model\MetadataInterface::METADATA_CACHE_SUFFIX'
    ],
    [
        'REQUEST_ID_PREFIX',
        'Magento\FullPageCache\Model\Processor',
        'Magento\FullPageCache\Model\Request\Identifier::REQUEST_ID_PREFIX'
    ],
    [
        'DESIGN_EXCEPTION_KEY',
        'Magento\FullPageCache\Model\Processor',
        'Magento\FullPageCache\Model\DesignPackage\Info::DESIGN_EXCEPTION_KEY'
    ],
    [
        'DESIGN_CHANGE_CACHE_SUFFIX',
        'Magento\FullPageCache\Model\Processor',
        'Magento\FullPageCache\Model\DesignPackage\Rules::DESIGN_CHANGE_CACHE_SUFFIX'
    ],
    ['XML_PATH_ACL_DENY_RULES', 'Magento_AdminGws_Model_Observer'],
    ['XML_PATH_VALIDATE_CALLBACK', 'Magento_AdminGws_Model_Observer'],
    ['XML_CHARSET_NODE', 'Magento_GiftCardAccount_Model_Pool'],
    ['XML_CHARSET_SEPARATOR', 'Magento_GiftCardAccount_Model_Pool'],
    ['XML_PATH_SKIP_GLOBAL_FIELDS', 'Magento_Logging_Model_Event_Changes'],
    [
        'XML_PATH_RESTRICTION_ENABLED',
        'Magento_WebsiteRestriction_Helper_Data',
        'Magento_WebsiteRestriction_Model_Config::XML_PATH_RESTRICTION_ENABLED'
    ],
    [
        'XML_PATH_RESTRICTION_MODE',
        'Magento_WebsiteRestriction_Helper_Data',
        'Magento_WebsiteRestriction_Model_Config::XML_PATH_RESTRICTION_MODE'
    ],
    [
        'XML_PATH_RESTRICTION_LANDING_PAGE',
        'Magento_WebsiteRestriction_Helper_Data',
        'Magento_WebsiteRestriction_Model_Config::XML_PATH_RESTRICTION_LANDING_PAGE'
    ],
    [
        'XML_PATH_RESTRICTION_HTTP_STATUS',
        'Magento_WebsiteRestriction_Helper_Data',
        'Magento_WebsiteRestriction_Model_Config::XML_PATH_RESTRICTION_HTTP_STATUS'
    ],
    [
        'XML_PATH_RESTRICTION_HTTP_REDIRECT',
        'Magento_WebsiteRestriction_Helper_Data',
        'Magento_WebsiteRestriction_Model_Config::XML_PATH_RESTRICTION_HTTP_REDIRECT'
    ],
    ['XML_NODE_RESTRICTION_ALLOWED_GENERIC', 'Magento_WebsiteRestriction_Helper_Data'],
    ['XML_NODE_RESTRICTION_ALLOWED_REGISTER', 'Magento_WebsiteRestriction_Helper_Data'],
    ['XML_PATH_CONTEXT_MENU_LAYOUTS', 'Magento\VersionsCms\Model\Hierarchy\Config'],
    ['XML_NODE_ALLOWED_CACHE', 'Magento\FullPageCache\Model\Processor'],
    [
        'XML_PATH_GRANT_CATALOG_CATEGORY_VIEW',
        'Magento\CatalogPermissions\Helper\Data',
        'Magento\CatalogPermissions\App\ConfigInterface::XML_PATH_GRANT_CATALOG_CATEGORY_VIEW'
    ],
    [
        'XML_PATH_GRANT_CATALOG_PRODUCT_PRICE',
        'Magento\CatalogPermissions\Helper\Data',
        'Magento\CatalogPermissions\App\ConfigInterface::XML_PATH_GRANT_CATALOG_PRODUCT_PRICE'
    ],
    [
        'XML_PATH_GRANT_CHECKOUT_ITEMS',
        'Magento\CatalogPermissions\Helper\Data',
        'Magento\CatalogPermissions\App\ConfigInterface::XML_PATH_GRANT_CHECKOUT_ITEMS'
    ],
    [
        'XML_PATH_DENY_CATALOG_SEARCH',
        'Magento\CatalogPermissions\Helper\Data',
        'Magento\CatalogPermissions\App\ConfigInterface::XML_PATH_DENY_CATALOG_SEARCH'
    ],
    [
        'XML_PATH_LANDING_PAGE',
        'Magento\CatalogPermissions\Helper\Data',
        'Magento\CatalogPermissions\App\ConfigInterface::XML_PATH_LANDING_PAGE'
    ],
    [
        'GRANT_ALL',
        'Magento\CatalogPermissions\Helper\Data',
        'Magento\CatalogPermissions\App\ConfigInterface::GRANT_ALL'
    ],
    [
        'GRANT_CUSTOMER_GROUP',
        'Magento\CatalogPermissions\Helper\Data',
        'Magento\CatalogPermissions\App\ConfigInterface::GRANT_CUSTOMER_GROUP'
    ],
    [
        'GRANT_NONE',
        'Magento\CatalogPermissions\Helper\Data',
        'Magento\CatalogPermissions\App\ConfigInterface::GRANT_NONE'
    ],
    ['XML_PATH_GRANT_BASE', 'Magento\CatalogPermissions\Model\ResourceModel\Permission\Index'],
    ['EVENT_TYPE_REINDEX_PRODUCTS', 'Magento\CatalogPermissions\Model\Permission\Index'],
    ['ENTITY_CATEGORY', 'Magento\CatalogPermissions\Model\Permission\Index'],
    ['ENTITY_PRODUCT', 'Magento\CatalogPermissions\Model\Permission\Index'],
    ['ENTITY_CONFIG', 'Magento\CatalogPermissions\Model\Permission\Index'],
    [
        'FORM_SELECT_ALL_VALUES',
        'Magento\CatalogPermissions\Model\Adminhtml\Observer',
        'Magento\CatalogPermissions\Block\Adminhtml\Catalog\Category\Tab\Permissions\Row::FORM_SELECT_ALL_VALUES'
    ],
    [
        'STATUS_NEW',
        'Magento\Invitation\Model\Invitation',
        'Magento\Invitation\Model\Invitation\Status::STATUS_NEW',
    ],
    [
        'STATUS_SENT',
        'Magento\Invitation\Model\Invitation',
        'Magento\Invitation\Model\Invitation\Status::STATUS_SENT',
    ],
    [
        'STATUS_ACCEPTED',
        'Magento\Invitation\Model\Invitation',
        'Magento\Invitation\Model\Invitation\Status::STATUS_ACCEPTED',
    ],
    [
        'STATUS_CANCELED',
        'Magento\Invitation\Model\Invitation',
        'Magento\Invitation\Model\Invitation\Status::STATUS_CANCELED',
    ],
    ['XML_PATH_DEFAULT_VALUES', 'Magento\TargetRule\Model\Rule'],
    [
        'BANNER_WIDGET_DISPLAY_FIXED',
        'Magento\Banner\Block\Widget\Banner',
        'Magento\Banner\Model\Config::BANNER_WIDGET_DISPLAY_FIXED'
    ],
    [
        'BANNER_WIDGET_DISPLAY_SALESRULE',
        'Magento\Banner\Block\Widget\Banner',
        'Magento\Banner\Model\Config::BANNER_WIDGET_DISPLAY_SALESRULE'
    ],
    [
        'BANNER_WIDGET_DISPLAY_CATALOGRULE',
        'Magento\Banner\Block\Widget\Banner',
        'Magento\Banner\Model\Config::BANNER_WIDGET_DISPLAY_CATALOGRULE'
    ],
    [
        'XML_PATH_REBUILD_ON_CATEGORY_SAVE',
        'Magento\VisualMerchandiser\Observer\CatalogCategorySaveBefore',
        'Magento\VisualMerchandiser\Model\Category\Builder::XML_PATH_REBUILD_ON_CATEGORY_SAVE'
    ],
    [
        'XML_PATH_REBUILD_ON_PRODUCT_SAVE',
        'Magento\VisualMerchandiser\Observer\CatalogProductSaveAfter',
        'Magento\VisualMerchandiser\Model\Category\Builder::XML_PATH_REBUILD_ON_PRODUCT_SAVE'
    ],
    [
        'CALLBACK',
        'Magento\Framework\MessageQueue\ConsumerConfiguration'
    ]
];
