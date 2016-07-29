<?php
/***
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Logging\Controller\Adminhtml\Logging;

class ExportXmlTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    public function setUp()
    {
        $this->resource = 'Magento_Logging::magento_logging_events';
        $this->uri = 'backend/admin/logging/exportxml';
        parent::setUp();
    }
}
