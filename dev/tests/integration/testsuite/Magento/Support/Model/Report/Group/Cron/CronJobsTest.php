<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Model\Report\Group\Cron;

class CronJobsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param string $namespace
     * @param string $expectedPath
     * @return void
     * @dataProvider getFilePathByNamespaceDataProvider
     */
    public function testGetFilePathByNamespace($namespace, $expectedPath)
    {
        /** @var \Magento\Support\Model\Report\Group\Cron\CronJobs $cronJobs */
        $cronJobs = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(\Magento\Support\Model\Report\Group\Cron\CronJobs::class);

        $this->assertTrue(
            strpos($cronJobs->getFilePathByNamespace($namespace), $expectedPath) !== false,
            'Cron Job path does not cantain expexted path'
        );
    }

    /**
     * @return array
     */
    public function getFilePathByNamespaceDataProvider()
    {
        return [
            [
                'namespace' => \Magento\Support\Model\Report\Group\Cron\CronJobs::class,
                'expectedPath' => 'Model/Report/Group/Cron/CronJobs.php'
            ],
            [
                'namespace' => '',
                'expectedPath' => 'n/a'
            ]
        ];
    }
}
