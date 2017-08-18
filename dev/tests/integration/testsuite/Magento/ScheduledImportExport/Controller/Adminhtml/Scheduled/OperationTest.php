<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ScheduledImportExport\Controller\Adminhtml\Scheduled;

/**
 * @magentoAppArea adminhtml
 */
class OperationTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * Set value of $_SERVER['HTTP_X_REQUESTED_WITH'] parameter here
     *
     * @var string
     */
    protected $_httpXRequestedWith;

    protected function setUp()
    {
        parent::setUp();

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->_httpXRequestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'];
        }
    }

    protected function tearDown()
    {
        if ($this->_httpXRequestedWith !== null) {
            $_SERVER['HTTP_X_REQUESTED_WITH'] = $this->_httpXRequestedWith;
        }

        parent::tearDown();
    }

    /**
     * Get possible entity types
     *
     * @return array
     */
    public function getEntityTypesDataProvider()
    {
        return [
            'products' => ['$entityType' => 'catalog_product'],
            'customers' => ['$entityType' => 'customer']
        ];
    }

    /**
     * Get some required fields list to check whether they are present on edit form
     *
     * @return array
     */
    public function getEditActionDataProvider()
    {
        return [
            'export' => [
                '$expectedContains' => [
                    'name',
                    'entity_type',
                    'file_format',
                    'server_type',
                    'file_path',
                    'freq',
                    'status',
                    'email_receiver',
                    'email_sender',
                    'email_template',
                    'email_copy_method',
                ],
            ]
        ];
    }

    /**
     * Test edit action
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/operation.php
     * @dataProvider getEditActionDataProvider
     *
     * @param array $expectedContains expected filed names list
     */
    public function testEditAction($expectedContains)
    {
        /** @var $collection \Magento\ScheduledImportExport\Model\ResourceModel\Scheduled\Operation\Collection */
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\ScheduledImportExport\Model\ResourceModel\Scheduled\Operation\Collection::class
        );
        $this->assertCount(1, $collection->getItems());
        $operation = $collection->getFirstItem();

        $this->dispatch('backend/admin/scheduled_operation/edit/id/' . $operation->getId());

        foreach ($expectedContains as $expectedFieldName) {
            $this->assertContains($expectedFieldName, $this->getResponse()->getBody());
        }
    }

    /**
     * Test cron action
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/operation.php
     * @magentoDataFixture Magento/Catalog/_files/products_new.php
     */
    public function testCronAction()
    {
        $this->markTestSkipped('This test failed on incorrect getcwd, and this is not a functionality problem');
        /** @var $collection \Magento\ScheduledImportExport\Model\ResourceModel\Scheduled\Operation\Collection */
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\ScheduledImportExport\Model\ResourceModel\Scheduled\Operation\Collection::class
        );
        $this->assertCount(1, $collection->getItems());
        $operation = $collection->getFirstItem();

        // Create export directory if not exist
        /** @var \Magento\Framework\Filesystem\Directory\Write $varDir */
        $varDir = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\Filesystem::class
        )->getDirectoryWrite(
            'var'
        );
        $varDir->create('export');

        // Change current working directory to allow save export results
        $cwd = getcwd();
        chdir($varDir->getAbsolutePath('export'));

        $this->dispatch('backend/admin/scheduled_operation/cron/operation/' . $operation->getId());

        // Restore current working directory
        chdir($cwd);

        $this->assertSessionMessages($this->isEmpty(), \Magento\Framework\Message\MessageInterface::TYPE_ERROR);
        $this->assertSessionMessages(
            $this->logicalNot($this->isEmpty()),
            \Magento\Framework\Message\MessageInterface::TYPE_SUCCESS
        );
    }

    /**
     * Test getFilter action
     *
     * @dataProvider getEntityTypesDataProvider
     *
     * @param string $entityType
     */
    public function testGetFilterAction($entityType)
    {
        // Provide X_REQUESTED_WITH header in response to mark next action as ajax
        $this->getRequest()->getHeaders()
            ->addHeaderLine('X_REQUESTED_WITH', 'XMLHttpRequest');

        $this->dispatch('backend/admin/scheduled_operation/getFilter/entity/' . $entityType);

        $this->assertContains('<div id="export_filter_grid"', $this->getResponse()->getBody());
    }
}
