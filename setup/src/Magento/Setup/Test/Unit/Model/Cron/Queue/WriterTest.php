<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Setup\Test\Unit\Model\Cron\Queue;

use Magento\Setup\Model\Cron\Queue\Writer;

class WriterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\Filesystem\Directory\ReadInterface
     */
    private $directoryWrite;

    /**
     * @var Writer
     */
    private $writer;

    public function setUp()
    {
        $this->filesystem = $this->createMock(\Magento\Framework\Filesystem::class);
        $directoryRead = $this->getMockForAbstractClass(
            \Magento\Framework\Filesystem\Directory\ReadInterface::class,
            [],
            '',
            false
        );
        $this->directoryWrite = $this->getMockForAbstractClass(
            \Magento\Framework\Filesystem\Directory\WriteInterface::class,
            [],
            '',
            false
        );
        $this->filesystem->expects($this->once())->method('getDirectoryRead')->willReturn($directoryRead);
        $this->filesystem->expects($this->once())->method('getDirectoryWrite')->willReturn($this->directoryWrite);
        $this->writer = new Writer($this->filesystem);
    }

    public function testWrite()
    {
        $this->directoryWrite->expects($this->once())->method('writeFile')->with('.update_queue.json', 'data');
        $this->writer->write('data');
    }
}
