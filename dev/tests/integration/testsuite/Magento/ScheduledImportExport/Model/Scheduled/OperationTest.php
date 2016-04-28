<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ScheduledImportExport\Model\Scheduled;

class OperationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\ScheduledImportExport\Model\Scheduled\Operation
     */
    protected $_model;

    /**
     * Set up before test
     */
    protected function setUp()
    {
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\ScheduledImportExport\Model\Scheduled\Operation'
        );
    }

    /**
     * Get possible operation types
     *
     * @return array
     */
    public function getOperationTypesDataProvider()
    {
        return ['import' => ['$operationType' => 'import'], 'export' => ['$operationType' => 'export']];
    }

    /**
     * Test getInstance() method
     *
     * @dataProvider getOperationTypesDataProvider
     * @param $operationType
     */
    public function testGetInstance($operationType)
    {
        $this->_model->setOperationType($operationType);
        $string = new \Magento\Framework\Stdlib\StringUtils();
        $this->assertInstanceOf(
            'Magento\ScheduledImportExport\Model\\' . $string->upperCaseWords($operationType),
            $this->_model->getInstance()
        );
    }

    /**
     * Test getHistoryFilePath() method in case when file info is not set
     *
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testGetHistoryFilePathException()
    {
        $this->_model->getHistoryFilePath();
    }

    /**
     * @magentoDataFixture Magento/ScheduledImportExport/_files/operation.php
     * @magentoDataFixture Magento/Catalog/_files/products_new.php
     */
    public function testRunAction()
    {
        $this->_model->load('export', 'operation_type');

        $fileInfo = $this->_model->getFileInfo();

        // Create export directory if not exist
        /** @var \Magento\Framework\Filesystem\Directory\Write $varDir */
        $varDir = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\Filesystem'
        )->getDirectoryWrite(
            'base'
        );
        $varDir->create($fileInfo['file_path']);

        // Change current working directory to allow save export results
        $cwd = getcwd();
        chdir($varDir->getAbsolutePath());

        $this->_model->run();

        $scheduledExport = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\ScheduledImportExport\Model\Export'
        );
        $scheduledExport->setEntity($this->_model->getEntityType());
        $scheduledExport->setOperationType($this->_model->getOperationType());
        $scheduledExport->setRunDate($this->_model->getLastRunDate());

        $filePath = $varDir->getAbsolutePath(
            $fileInfo['file_path']
        ) . '/' . $scheduledExport->getScheduledFileName() . '.' . $fileInfo['file_format'];
        $this->assertFileExists($filePath);

        // Restore current working directory
        chdir($cwd);
    }
}
