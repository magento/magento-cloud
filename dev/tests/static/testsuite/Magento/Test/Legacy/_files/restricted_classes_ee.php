<?php
/**
 * Classes that are restricted to use directly.
 * A <replacement> will be suggested to be used instead.
 * Use <whitelist> to specify files and directories that are allowed to use restricted classes.
 *
 * Format: array(<class_name>, <replacement>[, array(<whitelist>)]])
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
return [
    'Zend_Db_Select' => [
        'exclude' => [
            ['type' => 'module', 'name' => 'Magento_ResourceConnections', 'path' => 'DB/Adapter/Pdo/MysqlProxy.php'],
        ]
    ]
];
