<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ScheduledImportExport\Model\Scheduled;

class OperationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\ScheduledImportExport\Model\Scheduled\Operation
     */
    protected $model;

    /**
     * Set up before test
     */
    protected function setUp()
    {
        $this->model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\ScheduledImportExport\Model\Scheduled\Operation::class
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
        $this->model->setOperationType($operationType);
        $string = new \Magento\Framework\Stdlib\StringUtils();
        $this->assertInstanceOf(
            'Magento\ScheduledImportExport\Model\\' . $string->upperCaseWords($operationType),
            $this->model->getInstance()
        );
    }

    /**
     * Test getHistoryFilePath() method in case when file info is not set
     *
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testGetHistoryFilePathException()
    {
        $this->model->getHistoryFilePath();
    }

    /**
     * @magentoDataFixture Magento/ScheduledImportExport/_files/operation.php
     */
    public function testSave()
    {
        /** @var \Magento\Framework\App\CacheInterface $cacheManager */
        $cacheManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Framework\App\CacheInterface::class
        );
        $cacheManager->save('test_data', 'test_data_id', ['crontab']);
        $this->assertEquals('test_data', $cacheManager->load('test_data_id'));
        $this->model->load('export', 'operation_type');
        $this->model->setStartTime('06:00:00');
        $this->model->save();
        $result = $cacheManager->load('test_data_id');
        $this->assertEmpty($result);
    }

    /**
     * @magentoDataFixture Magento/ScheduledImportExport/_files/operation.php
     * @magentoDataFixture Magento/Catalog/_files/products_new.php
     */
    public function testRunAction()
    {
        $this->model->load('export', 'operation_type');

        $fileInfo = $this->model->getFileInfo();

        // Create export directory if not exist
        /** @var \Magento\Framework\Filesystem\Directory\Write $varDir */
        $varDir = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\Filesystem::class
        )->getDirectoryWrite(
            'base'
        );
        $varDir->create($fileInfo['file_path']);

        // Change current working directory to allow save export results
        $cwd = getcwd();
        chdir($varDir->getAbsolutePath());

        $this->model->run();

        $scheduledExport = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\ScheduledImportExport\Model\Export::class
        );
        $scheduledExport->setEntity($this->model->getEntityType());
        $scheduledExport->setOperationType($this->model->getOperationType());
        $scheduledExport->setRunDate($this->model->getLastRunDate());

        $filePath = $varDir->getAbsolutePath(
            $fileInfo['file_path']
        ) . '/' . $scheduledExport->getScheduledFileName() . '.' . $fileInfo['file_format'];
        $this->assertFileExists($filePath);

        // Restore current working directory
        chdir($cwd);
    }
}
