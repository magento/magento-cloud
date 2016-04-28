<?php
/**
 * Entry point for static resources (JS, CSS, etc.)
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
// This is a copy of static.php because the Platform.sh Nginx configuration
// has a bug that prevents us from using both /static.php and /static.
require __DIR__ . '/../app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
/** @var \Magento\Framework\App\StaticResource $app */
$app = $bootstrap->createApplication('Magento\Framework\App\StaticResource');
$bootstrap->run($app);
