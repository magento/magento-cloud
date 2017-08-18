<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Model;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use Magento\Support\Model\Report;

/**
 * Test for \Magento\Support\Model\Report
 *
 * @magentoAppIsolation enabled
 * @magentoDbIsolation enabled
 */
class ReportTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * Test report save with serialization
     *
     * @return void
     */
    public function testReportSaveWithSerialization()
    {
        $groups = ['attributes'];

        $createReport = $this->objectManager->create(Report::class);
        $createReport->generate($groups);
        $createReportData = $createReport->getReportData();
        $this->assertNotEmpty($createReportData);
        $createReport->save();
        $reportId = $createReport->getId();

        $checkReport = $this->objectManager->create(Report::class);
        $checkReport->load($reportId);
        $checkReportData = $checkReport->getReportData();
        $this->assertNotEmpty($checkReportData);

        $this->assertEquals($createReportData, $checkReportData);
    }
}
