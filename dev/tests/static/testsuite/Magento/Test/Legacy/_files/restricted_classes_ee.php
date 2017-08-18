<?php
/**
 * Classes that are restricted to use directly.
 * A <replacement> will be suggested to be used instead.
 * Use <whitelist> to specify files and directories that are allowed to use restricted classes.
 *
 * Format: array(<class_name>, <replacement>[, array(<whitelist>)]])
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
return [
    'Zend_Db_Select' => [
        'exclude' => [
            [
                'type' => 'module',
                'name' => 'Magento_ResourceConnections',
                'path' => 'DB/Adapter/Pdo/MysqlProxy.php'
            ],
        ]
    ],
    'Magento\Framework\Serialize\Serializer\Serialize' => [
        'exclude' => [
            [
                'type' => 'library',
                'name' => 'magento/framework',
                'path' => 'Test/Unit/FlagTest.php'
            ],
            [
                'type' => 'module',
                'name' => 'Magento_Staging',
                'path' => 'Test/Unit/Model/Update/FlagTest.php'
            ]
        ]
    ],
    'ArrayObject' => [
        'exclude' => [
            [
                'type' => 'module',
                'name' => 'Magento_MultipleWishlist',
                'path' => 'Test/Unit/Model/Search/Strategy/EmailTest.php'
            ],
            [
                'type' => 'module',
                'name' => 'Magento_Rma',
                'path' => 'Test/Unit/Model/RmaRepositoryTest.php'
            ],
            [
                'type' => 'module',
                'name' => 'Magento_Rma',
                'path' => 'Test/Unit/Model/Status/HistoryRepositoryTest.php'
            ]
        ]
    ]
];
