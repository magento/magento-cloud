<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
$baseUrl = '127.0.0.1:8080';

return [
    'MAGENTO_CLOUD_RELATIONSHIPS' => [
        'database' => [
            0 => [
                'host' => 'db',
                'path' => 'magento2',
                'password' => 'magento2',
                'username' => 'magento2',
                'port' => '3306',
            ],
        ],
    ],
    'MAGENTO_CLOUD_ROUTES' => [
        "http://{$baseUrl}/" => [
            'type' => 'upstream',
            'original_url' => 'http://{default}',
        ],
        "https://{$baseUrl}/" => [
            'type' => 'upstream',
            'original_url' => 'https://{default}',
        ],
    ],
    'MAGENTO_CLOUD_VARIABLES' => [
        'ADMIN_EMAIL' => 'admin@example.com',
    ],
];
