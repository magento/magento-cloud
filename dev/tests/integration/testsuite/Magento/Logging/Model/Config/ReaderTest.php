<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Logging\Model\Config;

use Magento\TestFramework\Helper\Bootstrap;

class ReaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Reader
     */
    private $model;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $fileResolver;

    protected function setUp()
    {
        $this->fileResolver = $this->getMockForAbstractClass(\Magento\Framework\Config\FileResolverInterface::class);
        $objectManager = Bootstrap::getObjectManager();
        $this->model = $objectManager->create(
            \Magento\Logging\Model\Config\Reader::class,
            ['fileResolver' => $this->fileResolver]
        );
    }

    public function testRead()
    {
        $this->fileResolver->expects($this->once())->method('get')->with('logging.xml', 'global')->willReturn(
            [file_get_contents(__DIR__ . '/_files/logging.xml')]
        );
        $expected = include __DIR__ . '/_files/expectedArray.php';
        $this->assertEquals($expected, $this->model->read('global'));
    }

    public function testMergeCompleteAndPartial()
    {
        $files = [
            file_get_contents(__DIR__ . '/_files/customerBalance.xml'),
            file_get_contents(__DIR__ . '/_files/Reward.xml'),
        ];
        $this->fileResolver->expects($this->once())->method('get')->with('logging.xml', 'global')->willReturn($files);
        $this->assertArrayHasKey('logging', $this->model->read('global'));
    }
}
