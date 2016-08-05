<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/**
 * @var \Magento\AdvancedSalesRule\Model\Indexer\SalesRule\Processor $processor
 */
$processor = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
    ->create('Magento\AdvancedSalesRule\Model\Indexer\SalesRule\Processor');
$processor->getIndexer()->setScheduled(true);
