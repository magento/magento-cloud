<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Setup\Test\Unit\Controller;

use \Magento\Setup\Controller\BackupActionItems;
use \Magento\Setup\Controller\ResponseTypeInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BackupActionItemsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Setup\Model\ObjectManagerProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManagerProvider;

    /**
     * @var \Magento\Setup\Model\WebLogger|\PHPUnit_Framework_MockObject_MockObject
     */
    private $log;

    /**
     * @var \Magento\Framework\Setup\BackupRollback|\PHPUnit_Framework_MockObject_MockObject
     */
    private $backupRollback;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList|\PHPUnit_Framework_MockObject_MockObject
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\Backup\Filesystem|\PHPUnit_Framework_MockObject_MockObject
     */
    private $filesystem;

    /**
     * Controller
     *
     * @var \Magento\Setup\Controller\BackupActionItems
     */
    private $controller;

    public function setUp()
    {
        $this->directoryList =
            $this->createMock(\Magento\Framework\App\Filesystem\DirectoryList::class);
        $this->objectManagerProvider =
            $this->createMock(\Magento\Setup\Model\ObjectManagerProvider::class);
        $this->backupRollback =
            $this->createPartialMock(\Magento\Framework\Setup\BackupRollback::class, ['getDBDiskSpace', 'dbBackup']);
        $objectManager = $this->createMock(\Magento\Framework\ObjectManagerInterface::class);
        $objectManager->expects($this->once())->method('create')->willReturn($this->backupRollback);
        $this->objectManagerProvider->expects($this->once())->method('get')->willReturn($objectManager);
        $this->log = $this->createMock(\Magento\Setup\Model\WebLogger::class);
        $this->filesystem = $this->createMock(\Magento\Framework\Backup\Filesystem::class);

        $this->controller = new BackupActionItems(
            $this->objectManagerProvider,
            $this->log,
            $this->directoryList,
            $this->filesystem
        );

        $request = $this->createMock(\Zend\Http\PhpEnvironment\Request::class);
        $response = $this->createMock(\Zend\Http\PhpEnvironment\Response::class);
        $routeMatch = $this->createMock(\Zend\Mvc\Router\RouteMatch::class);

        $mvcEvent = $this->createMock(\Zend\Mvc\MvcEvent::class);
        $mvcEvent->expects($this->any())->method('setRequest')->with($request)->willReturn($mvcEvent);
        $mvcEvent->expects($this->any())->method('setResponse')->with($response)->willReturn($mvcEvent);
        $mvcEvent->expects($this->any())->method('setTarget')->with($this->controller)->willReturn($mvcEvent);
        $mvcEvent->expects($this->any())->method('getRouteMatch')->willReturn($routeMatch);
        $contentArray = '{"options":{"code":false,"media":false,"db":true}}';
        $request->expects($this->any())->method('getContent')->willReturn($contentArray);

        $this->controller->setEvent($mvcEvent);
        $this->controller->dispatch($request, $response);
    }

    public function testCheckAction()
    {
        $this->backupRollback->expects($this->once())->method('getDBDiskSpace')->willReturn(500);
        $this->directoryList->expects($this->once())->method('getPath')->willReturn(__DIR__);
        $this->filesystem->expects($this->once())->method('validateAvailableDiscSpace');
        $jsonModel = $this->controller->checkAction();
        $this->assertInstanceOf(\Zend\View\Model\JsonModel::class, $jsonModel);
        $variables = $jsonModel->getVariables();
        $this->assertArrayHasKey('responseType', $variables);
        $this->assertEquals(ResponseTypeInterface::RESPONSE_TYPE_SUCCESS, $variables['responseType']);
        $this->assertArrayHasKey('size', $variables);
        $this->assertEquals(true, $variables['size']);
    }

    public function testCheckActionWithError()
    {
        $this->directoryList->expects($this->once())->method('getPath')->willReturn(__DIR__);
        $this->filesystem->expects($this->once())->method('validateAvailableDiscSpace')->will(
            $this->throwException(new \Exception("Test error message"))
        );
        $jsonModel = $this->controller->checkAction();
        $this->assertInstanceOf(\Zend\View\Model\JsonModel::class, $jsonModel);
        $variables = $jsonModel->getVariables();
        $this->assertArrayHasKey('responseType', $variables);
        $this->assertEquals(ResponseTypeInterface::RESPONSE_TYPE_ERROR, $variables['responseType']);
        $this->assertArrayHasKey('error', $variables);
        $this->assertEquals("Test error message", $variables['error']);
    }

    public function testCreateAction()
    {
        $this->backupRollback->expects($this->once())->method('dbBackup')->willReturn('backup/path/');
        $jsonModel = $this->controller->createAction();
        $this->assertInstanceOf(\Zend\View\Model\JsonModel::class, $jsonModel);
        $variables = $jsonModel->getVariables();
        $this->assertArrayHasKey('responseType', $variables);
        $this->assertEquals(ResponseTypeInterface::RESPONSE_TYPE_SUCCESS, $variables['responseType']);
        $this->assertArrayHasKey('files', $variables);
        $this->assertEquals(['backup/path/'], $variables['files']);
    }

    public function testIndexAction()
    {
        $model = $this->controller->indexAction();
        $this->assertInstanceOf(\Zend\View\Model\ViewModel::class, $model);
    }
}
