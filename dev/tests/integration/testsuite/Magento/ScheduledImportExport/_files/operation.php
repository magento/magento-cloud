<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var $operation \Magento\ScheduledImportExport\Model\Scheduled\Operation */
$operation = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\ScheduledImportExport\Model\Scheduled\Operation::class
);

$data = [
    'operation_type' => 'export',
    'name' => 'Test Export ' . microtime(),
    'entity_type' => 'catalog_product',
    'file_info' => ['file_format' => 'csv', 'server_type' => 'file', 'file_path' => 'export'],
    'start_time' => '00:00:00',
    'freq' => \Magento\Cron\Model\Config\Source\Frequency::CRON_DAILY,
    'status' => '1',
    'email_receiver' => 'general',
    'email_sender' => 'general',
    'email_template' => 'magento_scheduledimportexport_export_failed',
    'email_copy_method' => 'bcc',
    'entity_attributes' => ['export_filter' => ['cost' => ['', '']]],
];
$operation->setId(1);
$operation->isObjectNew(true);
$operation->setData($data);
$operation->save();
