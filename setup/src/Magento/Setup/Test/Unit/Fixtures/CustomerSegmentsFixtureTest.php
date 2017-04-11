<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Setup\Test\Unit\Fixtures;

use \Magento\Setup\Fixtures\CustomerSegmentsFixture;

class CustomerSegmentsFixtureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Setup\Fixtures\FixtureModel
     */
    private $fixtureModelMock;

    /**
     * @var \Magento\Setup\Fixtures\CartPriceRulesFixture
     */
    private $model;

    public function setUp()
    {
        $this->fixtureModelMock = $this->getMock('\Magento\Setup\Fixtures\FixtureModel', [], [], '', false);

        $this->model = new CustomerSegmentsFixture($this->fixtureModelMock);
    }

    public function testExecute()
    {
        $contextMock = $this->getMock('\Magento\Framework\Model\ResourceModel\Db\Context', [], [], '', false);
        $abstractDbMock = $this->getMockForAbstractClass(
            '\Magento\Framework\Model\ResourceModel\Db\AbstractDb',
            [$contextMock],
            '',
            true,
            true,
            true,
            ['getAllChildren']
        );
        $abstractDbMock->expects($this->once())
            ->method('getAllChildren')
            ->will($this->returnValue([1]));

        $storeMock = $this->getMock('\Magento\Store\Model\Store', [], [], '', false);
        $storeMock->expects($this->once())
            ->method('getRootCategoryId')
            ->will($this->returnValue(2));

        $websiteMock = $this->getMock('\Magento\Store\Model\Website', [], [], '', false);
        $websiteMock->expects($this->once())
            ->method('getGroups')
            ->will($this->returnValue([$storeMock]));
        $websiteMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('website_id'));

        $storeManagerMock = $this->getMock('Magento\Store\Model\StoreManager', [], [], '', false);
        $storeManagerMock->expects($this->once())
            ->method('getWebsites')
            ->will($this->returnValue([$websiteMock]));

        $categoryMock = $this->getMock('Magento\Catalog\Model\Category', [], [], '', false);
        $categoryMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($abstractDbMock));
        $categoryMock->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('path/to/file'));
        $categoryMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('category_id'));

        $ruleModelMock = $this->getMock('\Magento\SalesRule\Model\Rule', [], [], '', false);
        $ruleModelFactoryMock = $this->getMock('\Magento\SalesRule\Model\RuleFactory', ['create'], [], '', false);
        $ruleModelFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($ruleModelMock);

        $segmentModelMock = $this->getMock('Magento\CustomerSegment\Model\Segment', [], [], '', false);
        $segmentModelFactoryMock = $this->getMock(
            'Magento\CustomerSegment\Model\SegmentFactory',
            [
                'create'
            ],
            [],
            '',
            false
        );
        $segmentModelFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($segmentModelMock);

        $objectValueMap = [
            ['Magento\SalesRule\Model\RuleFactory', $ruleModelFactoryMock],
            ['Magento\CustomerSegment\Model\SegmentFactory', $segmentModelFactoryMock],
            ['Magento\Catalog\Model\Category', $categoryMock]
        ];

        $objectManagerMock = $this->getMock('Magento\Framework\ObjectManager\ObjectManager', [], [], '', false);
        $objectManagerMock->expects($this->once())
            ->method('create')
            ->will($this->returnValue($storeManagerMock));
        $objectManagerMock->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($objectValueMap));

        $valueMap = [
            ['customer_segment_rules', 0, 1],
            ['cart_price_rules', 0, 1],
            ['customer_segments', 1, 1]
        ];

        $this->fixtureModelMock
            ->expects($this->any())
            ->method('getValue')
            ->will($this->returnValueMap($valueMap));
        $this->fixtureModelMock
            ->expects($this->any())
            ->method('getObjectManager')
            ->will($this->returnValue($objectManagerMock));

        $this->model->execute();
    }

    public function testNoFixtureConfigValue()
    {
        $ruleMock = $this->getMock('\Magento\SalesRule\Model\Rule', [], [], '', false);
        $ruleMock->expects($this->never())->method('save');

        $objectManagerMock = $this->getMock('Magento\Framework\ObjectManager\ObjectManager', [], [], '', false);
        $objectManagerMock->expects($this->never())
            ->method('get')
            ->with($this->equalTo('Magento\SalesRule\Model\Rule'))
            ->willReturn($ruleMock);

        $this->fixtureModelMock
            ->expects($this->never())
            ->method('getObjectManager')
            ->willReturn($objectManagerMock);
        $this->fixtureModelMock
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(0);

        $this->model->execute();
    }

    public function testGenerateCustomerSegments()
    {
        $segmentModelMock = $this->getMock(
            'Magento\CustomerSegment\Model\Segment',
            [
                'getSegmentId', 'save', 'getApplyTo', 'matchCustomers', 'loadPost'
            ],
            [],
            '',
            false
        );

        $data1 = [
            'name'          => 'Customer Segment 0',
            'website_ids'   => [1],
            'is_active'     => '1',
            'apply_to'      => 0,
        ];
        $segmentModelMock->expects($this->at(0))
            ->method('loadPost')
            ->with($data1);
        $segmentModelMock->expects($this->once())
            ->method('getSegmentId')
            ->willReturn(1);
        $data2 = [
            'name'          => 'Customer Segment 0',
            'segment_id'    => 1,
            'website_ids'   => [1],
            'is_active'     => '1',
            'conditions'    => [
                1 => [
                    'type' => 'Magento\\CustomerSegment\\Model\\Segment\\Condition\\Combine\\Root',
                    'aggregator' => 'any',
                    'value' => '1',
                    'new_child' => '',
                ],
                '1--1' => [
                    'type' => 'Magento\\CustomerSegment\\Model\\Segment\\Condition\\Customer\\Attributes',
                    'attribute' => 'email',
                    'operator' => '==',
                    'value' => 'user_1@example.com',
                ]
            ]
        ];
        $segmentModelMock->expects($this->at(3))
            ->method('loadPost')
            ->with($data2);
        $segmentModelMock->expects($this->exactly(2))
            ->method('save');
        $segmentModelFactoryMock = $this->getMock(
            'Magento\CustomerSegment\Model\SegmentFactory',
            [
                'create'
            ],
            [],
            '',
            false
        );
        $segmentModelFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($segmentModelMock);

        $objectValueMap = [
            ['Magento\CustomerSegment\Model\SegmentFactory', $segmentModelFactoryMock]
        ];

        $objectManagerMock = $this->getMock('Magento\Framework\ObjectManager\ObjectManager', [], [], '', false);
        $objectManagerMock->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($objectValueMap));

        $valueMap = [
            ['customers', 0, 1]
        ];

        $this->fixtureModelMock
            ->expects($this->any())
            ->method('getValue')
            ->will($this->returnValueMap($valueMap));
        $this->fixtureModelMock
            ->expects($this->any())
            ->method('getObjectManager')
            ->will($this->returnValue($objectManagerMock));

        $reflection = new \ReflectionClass($this->model);
        $reflectionProperty = $reflection->getProperty('customerSegmentsCount');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->model, 1);

        $this->model->generateCustomerSegments();
    }

    public function testGenerateSegmentCondition()
    {
        $firstCondition = [
            'type'      => 'Magento\\SalesRule\\Model\\Rule\\Condition\\Product',
            'attribute' => 'category_ids',
            'operator'  => '==',
            'value'     => null,
        ];

        $secondCondition = [
            'type'      => 'Magento\\SalesRule\\Model\\Rule\\Condition\\Address',
            'attribute' => 'base_subtotal',
            'operator'  => '>=',
            'value'     => 0,
        ];

        $thirdCondition = [
            'type'      => 'Magento\\CustomerSegment\\Model\\Segment\\Condition\\Segment',
            'operator'  => '==',
            'value'     => 1,
        ];
        $expected = [
            'conditions' => [
                1 => [
                    'type' => 'Magento\\SalesRule\\Model\\Rule\\Condition\\Combine',
                    'aggregator' => 'all',
                    'value' => '1',
                    'new_child' => '',
                ],
                '1--1'=> [
                    'type' => 'Magento\\SalesRule\\Model\\Rule\\Condition\\Product\\Found',
                    'aggregator' => 'all',
                    'value' => '1',
                    'new_child' => '',
                ],
                '1--1--1' => $firstCondition,
                '1--2' => $secondCondition,
                '1--3' => $thirdCondition,
            ],
            'actions' => [
                1 => [
                    'type' => 'Magento\\SalesRule\\Model\\Rule\\Condition\\Product\\Combine',
                    'aggregator' => 'all',
                    'value' => '1',
                    'new_child' => '',
                ],
            ]
        ];

        $reflection = new \ReflectionClass($this->model);
        $reflectionProperty = $reflection->getProperty('customerSegmentsCount');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->model, 1);
        $result = $this->model->generateSegmentCondition(0, [0]);
        $this->assertSame($expected, $result);
    }

    public function testGetActionTitle()
    {
        $this->assertSame('Generating customer segments and rules', $this->model->getActionTitle());
    }

    public function testIntroduceParamLabels()
    {
        $this->assertSame([
            'customer_segment_rules' => 'Customer Segments and Rules'
        ], $this->model->introduceParamLabels());
    }
}
