<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Setup\Test\Unit\Module\Dependency\Report\Writer\Csv;

class AbstractWriterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Setup\Module\Dependency\Report\Writer\Csv\AbstractWriter|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $writer;

    /**
     * @var \Magento\Framework\File\Csv|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $csvMock;

    protected function setUp()
    {
        $this->csvMock = $this->createMock(\Magento\Framework\File\Csv::class);

        $this->writer = $this->getMockForAbstractClass(
            \Magento\Setup\Module\Dependency\Report\Writer\Csv\AbstractWriter::class,
            ['writer' => $this->csvMock]
        );
    }

    public function testWrite()
    {
        $options = ['report_filename' => 'some_filename'];
        $configMock = $this->createMock(\Magento\Setup\Module\Dependency\Report\Data\ConfigInterface::class);
        $preparedData = ['foo', 'baz', 'bar'];

        $this->writer->expects(
            $this->once()
        )->method(
            'prepareData'
        )->with(
            $configMock
        )->will(
            $this->returnValue($preparedData)
        );
        $this->csvMock->expects($this->once())->method('saveData')->with($options['report_filename'], $preparedData);

        $this->writer->write($options, $configMock);
    }

    /**
     * @param array $options
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Writing error: Passed option "report_filename" is wrong.
     * @dataProvider dataProviderWrongOptionReportFilename
     */
    public function testWriteWithWrongOptionReportFilename($options)
    {
        $configMock = $this->createMock(\Magento\Setup\Module\Dependency\Report\Data\ConfigInterface::class);

        $this->writer->write($options, $configMock);
    }

    /**
     * @return array
     */
    public function dataProviderWrongOptionReportFilename()
    {
        return [
            [['report_filename' => '']],
            [['there_are_no_report_filename' => 'some_name']]
        ];
    }
}
