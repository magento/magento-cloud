<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

return [
    'logging' => [
        'actions' => [
            'apply_coupon' => ['label' => 'Apply Coupon'],
            'add_to_cart' => ['label' => 'Add to Cart'],
        ],
        'enterprise_checkout' => [
            'label' => 'Shopping Cart Management',
            'expected_models' => [
                'Enterprise_GiftRegistry_Model_Entity' => [],
                'Enterprise_GiftRegistry_Model_Item' => [],
            ],
            'actions' => [
                'adminhtml_checkout_index' => [
                    'group_name' => 'enterprise_checkout',
                    'action' => 'view',
                    'controller_action' => 'adminhtml_checkout_index',
                    'expected_models' => [\Magento\Quote\Model\Quote::class => []],
                ],
                'adminhtml_checkout_applyCoupon' => [
                    'group_name' => 'enterprise_checkout',
                    'action' => 'apply_coupon',
                    'controller_action' => 'adminhtml_checkout_applyCoupon',
                    'post_dispatch' => 'postDispatchAdminCheckoutApplyCoupon',
                    'expected_models' => [\Magento\Quote\Model\Quote::class => []],
                ],
                'adminhtml_checkout_updateItems' => [
                    'group_name' => 'enterprise_checkout',
                    'action' => 'save',
                    'controller_action' => 'adminhtml_checkout_updateItems',
                    'skip_on_back' => [0 => 'adminhtml_cms_page_version_edit'],
                    'expected_models' => [\Magento\Quote\Model\Quote\Item::class => []],
                ],
                'adminhtml_checkout_addToCart' => [
                    'group_name' => 'enterprise_checkout',
                    'action' => 'add_to_cart',
                    'controller_action' => 'adminhtml_checkout_addToCart',
                    'expected_models' => [
                        \Magento\Quote\Model\Quote\Item::class => ['additional_data' => ['item_id', 'quote_id']],
                    ],
                ],
                'customer_index_save' => [
                    'group_name' => 'enterprise_checkout',
                    'skip_on_back' => ['adminhtml_customerbalance_form', 'customer_index_edit'],
                    'expected_models' => [
                        'Enterprise_CustomerBalance_Model_Balance' => [],
                        '@' => ['extends' => 'merge'],
                    ],
                    'action' => 'save',
                    'controller_action' => 'customer_index_save',
                ],
                'adminhtml_customersegment_match' => [
                    'group_name' => 'enterprise_checkout',
                    'action' => 'refresh_data',
                    'post_dispatch' => 'Enterprise_CustomerSegment_Model_Logging::postDispatchCustomerSegmentMatch',
                    'controller_action' => 'adminhtml_customersegment_match',
                ],
            ],
        ],
        'customer' => [
            'label' => 'Customers',
            'expected_models' => [\Magento\Customer\Model\Customer::class => [
                    'skip_data' => ['new_password', 'password', 'password_hash'],
                ],
            ],
            'actions' => [
                'customer_index_edit' => [
                    'group_name' => 'customer',
                    'action' => 'view',
                    'controller_action' => 'customer_index_edit',
                ],
                'customer_index_save' => [
                    'group_name' => 'customer',
                    'action' => 'save',
                    'controller_action' => 'customer_index_save',
                    'skip_on_back' => ['customer_index_edit'],
                ],
            ],
        ],
    ]
];
