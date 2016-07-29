<?php
/***
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SalesArchive\Controller\Adminhtml\Archive;

class MassAddTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    public function setUp()
    {
        $this->resource = 'Magento_SalesArchive::add';
        $this->uri = 'backend/sales/archive/massadd';
        parent::setUp();
    }
}
