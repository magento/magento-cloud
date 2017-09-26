<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Setup\Test\Unit\Console\Command;

use Magento\Framework\Module\ModuleList;
use Magento\Setup\Console\Command\ConfigSetCommand;
use Symfony\Component\Console\Tester\CommandTester;

class ConfigSetCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Setup\Model\ConfigModel
     */
    private $configModel;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\App\DeploymentConfig
     */
    private $deploymentConfig;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Setup\Console\Command\ConfigSetCommand
     */
    private $command;

    public function setUp()
    {
        $option = $this->createMock(\Magento\Framework\Setup\Option\TextConfigOption::class);
        $option
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('db-host'));
        $this->configModel = $this->createMock(\Magento\Setup\Model\ConfigModel::class);
        $this->configModel
            ->expects($this->exactly(2))
            ->method('getAvailableOptions')
            ->will($this->returnValue([$option]));
        $moduleList = $this->createMock(\Magento\Framework\Module\ModuleList::class);
        $this->deploymentConfig = $this->createMock(\Magento\Framework\App\DeploymentConfig::class);
        $this->command = new ConfigSetCommand($this->configModel, $moduleList, $this->deploymentConfig);
    }

    public function testExecuteNoInteractive()
    {
        $this->deploymentConfig
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue(null));
        $this->configModel
            ->expects($this->once())
            ->method('process')
            ->with(['db-host' => 'host']);
        $commandTester = new CommandTester($this->command);
        $commandTester->execute(['--db-host' => 'host']);
        $this->assertSame(
            'You saved the new configuration.' . PHP_EOL,
            $commandTester->getDisplay()
        );
    }

    public function testExecuteInteractiveWithYes()
    {
        $this->deploymentConfig
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue('localhost'));
        $this->configModel
            ->expects($this->once())
            ->method('process')
            ->with(['db-host' => 'host']);
        $this->checkInteraction(true);
    }

    public function testExecuteInteractiveWithNo()
    {
        $this->deploymentConfig
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue('localhost'));
        $this->configModel
            ->expects($this->once())
            ->method('process')
            ->with([]);
        $this->checkInteraction(false);
    }

    /**
     * Checks interaction with users on CLI
     *
     * @param bool $interactionType
     * @return void
     */
    private function checkInteraction($interactionType)
    {
        $dialog = $this->createMock(\Symfony\Component\Console\Helper\DialogHelper::class);
        $dialog
            ->expects($this->once())
            ->method('askConfirmation')
            ->will($this->returnValue($interactionType));

        /** @var \Symfony\Component\Console\Helper\HelperSet|\PHPUnit_Framework_MockObject_MockObject $helperSet */
        $helperSet = $this->createMock(\Symfony\Component\Console\Helper\HelperSet::class);
        $helperSet
            ->expects($this->once())
            ->method('get')
            ->with('dialog')
            ->will($this->returnValue($dialog));
        $this->command->setHelperSet($helperSet);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(['--db-host' => 'host']);
        if ($interactionType) {
            $message = 'You saved the new configuration.' . PHP_EOL;
        } else {
            $message = 'You made no changes to the configuration.'.PHP_EOL;
        }
        $this->assertSame(
            $message,
            $commandTester->getDisplay()
        );
    }
}
