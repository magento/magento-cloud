<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Staging\Model\Event;

use Magento\TestFramework\Helper\Bootstrap;

class ManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\Event\ConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $eventConfigMock;

    /**
     * @var \Magento\Framework\Event\InvokerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $invokerMock;

    /**
     * @var \Magento\Staging\Model\VersionManagerFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $versionManagerFactoryMock;

    /**
     * @var \Magento\Staging\Model\VersionManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $versionManagerMock;

    /**
     * Create basic mock objects
     */
    protected function setUp()
    {
        $this->invokerMock =
            $this->createPartialMock(\Magento\Framework\Event\Invoker\InvokerDefault::class, ['dispatch']);
        $this->eventConfigMock =
            $this->createPartialMock(\Magento\Framework\Event\Config::class, ['getObservers']);
        $this->versionManagerMock =
            $this->createPartialMock(\Magento\Staging\Model\VersionManager::class, ['isPreviewVersion']);
        $this->versionManagerFactoryMock =
            $this->createPartialMock(\Magento\Staging\Model\VersionManagerFactory::class, ['create']);
        $this->versionManagerFactoryMock->expects($this->any())
            ->method('create')
            ->will($this->returnValue($this->versionManagerMock));
    }

    /**
     * Test that banned event will not be executed with staging preview version
     *
     * @param bool $isPreviewVersion
     * @dataProvider previewVersionDataProvider
     */
    public function testBannedEvent($isPreviewVersion)
    {
        $this->versionManagerMock->expects($this->once())
            ->method('isPreviewVersion')
            ->willReturn($isPreviewVersion);

        if ($isPreviewVersion) {
            $this->eventConfigMock->expects($this->never())
                ->method('getObservers');
        } else {
            $this->eventConfigMock->expects($this->once())
                ->method('getObservers')
                ->willReturn([]);
        }

        $eventManager = $this->createEventManager(['banned_event1' => 'banned_event1']);
        $eventManager->dispatch('banned_event1');
    }

    /**
     * Test that banned observer will not be executed with staging preview version
     *
     * @param bool $isPreviewVersion
     * @dataProvider previewVersionDataProvider
     */
    public function testBannedObserver($isPreviewVersion)
    {
        $this->versionManagerMock->expects($this->exactly(1))
            ->method('isPreviewVersion')
            ->willReturn($isPreviewVersion);

        $this->eventConfigMock->expects($this->once())
            ->method('getObservers')
            ->with('banned_event1')
            ->willReturn([['name' => 'banned_observer1']]);

        if ($isPreviewVersion) {
            $this->invokerMock->expects($this->never())->method('dispatch');
        } else {
            $this->invokerMock->expects($this->once())->method('dispatch');
        }

        $eventManager = $this->createEventManager([], ['banned_event1' => ['banned_observer1' => 'banned_observer1']]);
        $eventManager->dispatch('banned_event1');
    }

    /**
     * Staging preview mode provider
     *
     * @return array
     */
    public function previewVersionDataProvider()
    {
        return [
            [false], // preview mode turned on
            [true] // preview mode turned off
        ];
    }

    /**
     * Craate event manager instance of event manager
     *
     * @param array $bannedEvents
     * @param array $bannedObservers
     * @return \Magento\Framework\Event\ManagerInterface
     */
    private function createEventManager($bannedEvents = [], $bannedObservers = [])
    {
        $objectManager = Bootstrap::getObjectManager();
        return $objectManager->create(
            \Magento\Staging\Model\Event\Manager::class,
            [
                'invoker' => $this->invokerMock,
                'eventConfig' => $this->eventConfigMock,
                'versionManagerFactory' => $this->versionManagerFactoryMock,
                'bannedEvents' => $bannedEvents,
                'bannedObservers' => $bannedObservers
            ]
        );
    }
}
