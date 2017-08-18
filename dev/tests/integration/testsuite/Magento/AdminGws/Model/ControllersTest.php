<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdminGws\Model;

/**
 * Test for Magento\AdminGws\Model\Controllers
 */
class ControllersTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @var \Magento\AdminGws\Model\Controllers
     */
    protected $model;

    /**
     * @var \Magento\AdminGws\Model\Role|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $roleMock;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var \Magento\Store\Model\StoreManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManagerMock;

    /**
     * @var \Magento\Framework\App\Request\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    protected function setUp()
    {
        parent::setUp();

        $this->roleMock = $this->createMock(\Magento\AdminGws\Model\Role::class);
        $this->registryMock = $this->createMock(\Magento\Framework\Registry::class);
        $this->storeManagerMock = $this->createMock(\Magento\Store\Model\StoreManager::class);
        $this->requestMock = $this->createMock(\Magento\Framework\App\Request\Http::class);

        $this->model = $this->_objectManager->create(
            \Magento\AdminGws\Model\Controllers::class,
            [
                'role' => $this->roleMock,
                'registry' => $this->registryMock,
                'storeManager' => $this->storeManagerMock,
                'request' => $this->requestMock
            ]
        );
    }

    protected function tearDown()
    {
        $this->roleMock = null;
        $this->registryMock = null;
        $this->storeManagerMock = null;
        $this->requestMock = null;
        $this->model = null;
        parent::tearDown();
    }

    /**
     * User role has access to specific store view scope. No redirect should be expected in this case.
     */
    public function testValidateSystemConfigValidStoreCodeWithStoreAccess()
    {
        $this->requestMock->expects($this->any())->method('getParam')->with('store')->will(
            $this->returnValue('testStore')
        );

        $storeMock = $this->getMockBuilder(\Magento\Store\Model\Store::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();
        $storeMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));

        $this->storeManagerMock->expects($this->any())
            ->method('getStore')
            ->will($this->returnValue($storeMock));

        $this->roleMock->expects($this->any())
            ->method('hasStoreAccess')
            ->will($this->returnValue(true));

        $this->model->validateSystemConfig();
    }

    /**
     * User role has access to specific website view scope. No redirect should be expected in this case.
     */
    public function testValidateSystemConfigValidWebsiteCodeWithWebsiteAccess()
    {
        $this->requestMock->expects($this->at(0))->method('getParam')->with('store')->will(
            $this->returnValue(null)
        );

        $this->requestMock->expects($this->at(1))->method('getParam')->with('website')->will(
            $this->returnValue('testWebsite')
        );

        $websiteMock = $this->getMockBuilder(\Magento\Store\Model\Website::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();
        $websiteMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));

        $this->storeManagerMock->expects($this->any())
            ->method('getWebsite')
            ->will($this->returnValue($websiteMock));

        $this->roleMock->expects($this->any())
            ->method('hasWebsiteAccess')
            ->will($this->returnValue(true));

        $this->model->validateSystemConfig();
    }

    /**
     * User role has no access to specific store view scope or website. Redirect to first allowed website
     */
    public function testValidateSystemConfigRedirectToWebsite()
    {
        $this->requestMock->expects($this->any())->method('getParam')->will(
            $this->returnValue(null)
        );

        $websiteMock = $this->getMockBuilder(\Magento\Store\Model\Website::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCode'])
            ->getMock();
        $websiteMock->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('default'));

        $storeMock = $this->getMockBuilder(\Magento\Store\Model\Store::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWebsite'])
            ->getMock();
        $storeMock->expects($this->any())
            ->method('getWebsite')
            ->will($this->returnValue($websiteMock));

        $this->storeManagerMock->expects($this->any())
            ->method('getDefaultStoreView')
            ->will($this->returnValue($storeMock));

        $this->roleMock->expects($this->any())
            ->method('getWebsiteIds')
            ->will($this->returnValue(true));

        $this->model->validateSystemConfig();
        $this->assertRedirect();
    }

    /**
     * User role has no access to specific store view scope or website. Redirect to first allowed store view.
     */
    public function testValidateSystemConfigRedirectToStore()
    {
        $this->requestMock->expects($this->any())->method('getParam')->will(
            $this->returnValue(null)
        );

        $websiteMock = $this->getMockBuilder(\Magento\Store\Model\Website::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCode'])
            ->getMock();
        $websiteMock->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('default'));

        $storeMock = $this->getMockBuilder(\Magento\Store\Model\Store::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWebsite', 'getCode'])
            ->getMock();
        $storeMock->expects($this->any())
            ->method('getWebsite')
            ->will($this->returnValue($websiteMock));
        $storeMock->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('base'));

        $this->storeManagerMock->expects($this->any())
            ->method('getDefaultStoreView')
            ->will($this->returnValue($storeMock));

        $this->roleMock->expects($this->any())
            ->method('getWebsiteIds')
            ->will($this->returnValue(false));

        $this->model->validateSystemConfig();
        $this->assertRedirect();
    }

    /**
     * Test when system store is validated to be matched
     */
    public function testValidateSystemStoreMatched()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(true));
        $this->model->validateSystemStore();
    }

    /**
     * Test "save" action when request is forwarded to website view
     */
    public function testValidateSystemStoreActionNameSaveForwardToWebsite()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('save'));
        $this->requestMock->expects($this->any())
            ->method('getParams')
            ->will($this->returnValue(['website' => 'testWebsite']));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->model->validateSystemStore();
    }

    /**
     * Test "save" action when request is forwarded to store view
     */
    public function testValidateSystemStoreActionNameSaveForwardToStore()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('save'));
        $this->requestMock->expects($this->any())
            ->method('getParams')
            ->will($this->returnValue(['website' => null, 'store' => 'testStore']));
        $this->roleMock->expects($this->any())
            ->method('getWebsiteIds')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->model->validateSystemStore();
    }

    /**
     * Test "newWebsite" action
     */
    public function testValidateSystemStoreActionNameNewWebsite()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('newWebsite'));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->model->validateSystemStore();
    }

    /**
     * Test "newGroup" action
     */
    public function testValidateSystemStoreActionNameNewGroup()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('newGroup'));
        $this->roleMock->expects($this->any())
            ->method('getWebsiteIds')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->model->validateSystemStore();
    }

    /**
     * Test "newStore" action
     */
    public function testValidateSystemStoreActionNameNewStore()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('newStore'));
        $this->roleMock->expects($this->any())
            ->method('getWebsiteIds')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->model->validateSystemStore();
    }

    /**
     * Test "editWebsite" action
     */
    public function testValidateSystemStoreActionNameEditWebsite()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('editWebsite'));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->roleMock->expects($this->any())
            ->method('hasWebsiteAccess')
            ->will($this->returnValue(null));
        $this->model->validateSystemStore();
    }

    /**
     * Test "editGroup" action
     */
    public function testValidateSystemStoreActionNameEditGroup()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('editGroup'));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->roleMock->expects($this->any())
            ->method('hasStoreGroupAccess')
            ->will($this->returnValue(null));
        $this->model->validateSystemStore();
    }

    /**
     * Test "editStore" action
     */
    public function testValidateSystemStoreActionNameEditStore()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('editStore'));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->roleMock->expects($this->any())
            ->method('hasStoreAccess')
            ->will($this->returnValue(null));
        $this->model->validateSystemStore();
    }

    /**
     * Test "deleteWebsite" action
     */
    public function testValidateSystemStoreActionNameDeleteWebsite()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('deleteWebsite'));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->model->validateSystemStore();
    }

    /**
     * Test "deleteWebsitePost" action
     */
    public function testValidateSystemStoreActionNameDeleteWebsitePost()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('deleteWebsitePost'));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->model->validateSystemStore();
    }

    /**
     * Test "deleteGroup" action
     */
    public function testValidateSystemStoreActionNameDeleteGroup()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('deleteGroup'));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->model->validateSystemStore();
    }

    /**
     * Test "deleteGroupPost" action with website access
     */
    public function testValidateSystemStoreActionNameDeleteGroupPostHasWebsiteAccess()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('deleteGroupPost'));
        $groupMock = $this->getMockBuilder(\Magento\Store\Model\Group::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWebsiteId'])
            ->getMock();
        $groupMock->expects($this->any())
            ->method('getWebsiteId')
            ->will($this->returnValue('testWebsite'));
        $this->roleMock->expects($this->any())
            ->method('getGroup')
            ->will($this->returnValue($groupMock));
        $this->roleMock->expects($this->any())
            ->method('hasWebsiteAccess')
            ->will($this->returnValue(true));
        $this->model->validateSystemStore();
    }

    /**
     * Test "deleteGroupPost" action with no website access
     */
    public function testValidateSystemStoreActionNameDeleteGroupPostNoWebsiteAccess()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('deleteGroupPost'));
        $groupMock = $this->getMockBuilder(\Magento\Store\Model\Group::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWebsiteId'])
            ->getMock();
        $groupMock->expects($this->any())
            ->method('getWebsiteId')
            ->will($this->returnValue('testWebsite'));
        $this->roleMock->expects($this->any())
            ->method('getGroup')
            ->will($this->returnValue($groupMock));
        $this->roleMock->expects($this->any())
            ->method('getGroup')
            ->will($this->returnValue(true));
        $this->roleMock->expects($this->any())
            ->method('hasWebsiteAccess')
            ->will($this->returnValue(false));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->model->validateSystemStore();
    }

    /**
     * Test "deleteStore" action
     */
    public function testValidateSystemStoreActionNameDeleteStore()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('deleteStore'));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->model->validateSystemStore();
    }

    /**
     * Test "deleteStorePost" action with website access
     */
    public function testValidateSystemStoreActionNameDeleteStorePostHasWebsiteAccess()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('deleteStorePost'));
        $storeMock = $this->getMockBuilder(\Magento\Store\Model\Store::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWebsiteId'])
            ->getMock();
        $storeMock->expects($this->any())
            ->method('getWebsiteId')
            ->will($this->returnValue('testWebsite'));
        $this->storeManagerMock->expects($this->any())
            ->method('getStore')
            ->will($this->returnValue($storeMock));
        $this->roleMock->expects($this->any())
            ->method('hasWebsiteAccess')
            ->will($this->returnValue(true));
        $this->model->validateSystemStore();
    }

    /**
     * Test "deleteStorePost" action with no website access
     */
    public function testValidateSystemStoreActionNameDeleteStorePostNoWebsiteAccess()
    {
        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue(null));
        $this->requestMock->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('deleteStorePost'));
        $storeMock = $this->getMockBuilder(\Magento\Store\Model\Store::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWebsiteId'])
            ->getMock();
        $storeMock->expects($this->any())
            ->method('getWebsiteId')
            ->will($this->returnValue('testWebsite'));
        $this->storeManagerMock->expects($this->any())
            ->method('getStore')
            ->will($this->returnValue($storeMock));
        $this->roleMock->expects($this->any())
            ->method('hasWebsiteAccess')
            ->will($this->returnValue(false));
        $this->requestMock->expects($this->any())
            ->method('setActionName')
            ->will($this->returnSelf());
        $this->model->validateSystemStore();
    }
}
