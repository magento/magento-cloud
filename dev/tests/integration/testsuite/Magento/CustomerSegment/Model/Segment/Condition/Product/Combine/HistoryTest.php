<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Model\Segment\Condition\Product\Combine;

use Magento\CustomerSegment\Model\Customer;
use Magento\CustomerSegment\Model\ResourceModel\Segment\Report\Detail\Collection;
use Magento\CustomerSegment\Model\SegmentFactory;
use Magento\Framework\Api\Search\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Registry;
use Magento\Sales\Model\OrderRepository;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class HistoryTest
 * @package Magento\CustomerSegment\Model\Segment\Condition\Product\Combine
 */
class HistoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\CustomerSegment\Model\Segment
     */
    protected $segment;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        /** @var SegmentFactory $segmentFactory */
        $segmentFactory = $this->objectManager->get(SegmentFactory::class);
        $this->segment = $segmentFactory->create();
        $this->segment->loadPost($this->getSegmentPostData());
        $this->segment->save(); // should be replaced after implementation of segment repository
    }

    /**
     * Test segment with order condition matches only customers that have orders matching these conditions
     * Covers MAGETWO-67619
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     * @magentoDataFixture Magento/Customer/_files/two_customers.php
     */
    public function testCustomerMatchByOrderedProducts()
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->objectManager->get(OrderRepository::class);
        $orders = $orderRepository->getList($this->objectManager->get(SearchCriteriaInterface::class))->getItems();
        $order = array_pop($orders);
        $order->setCustomerId(1)->setCustomerIsGuest(false);
        $orderRepository->save($order);

        $this->segment->matchCustomers();

        /** @var Registry $registry */
        $registry = $this->objectManager->get(Registry::class);
        $registry->register('current_customer_segment', $this->segment);

        /** @var Collection $gridCollection */
        $gridCollection = $this->objectManager->get(Collection::class);

        $gridCollection->loadData();

        $this->assertCustomerCollectionData($gridCollection->getData());

        /** @var Customer $customer */
        $customer = $this->objectManager->get(Customer::class);

        // Emulate other customer login event is processed
        $customer->processCustomerEvent('customer_login', 2);

        // recreate collection as it is loading only if isLoaded flag is reset
        /** @var Collection $gridCollection */
        $gridCollection->resetData();
        $gridCollection->loadData();

        $this->assertCustomerCollectionData($gridCollection->getData());

        // Process invalid customer login
        $customer->processCustomerEvent('customer_login', null);

        $gridCollection->resetData();
        $gridCollection->loadData();

        $this->assertCustomerCollectionData($gridCollection->getData());
    }

    /**
     * Perform assertions on collection data
     *
     * @param $data
     *
     * @return void
     */
    protected function assertCustomerCollectionData($data)
    {
        $this->assertNotEmpty($data, 'Segment customer matching result is empty');
        $this->assertCount(1, $data, 'Segment should match only 1 costomer');
        $customerData = $data[0];
        $this->assertEquals('1', $customerData['entity_id'], 'Customer ID is not matching.');
        $this->assertEquals('1', $customerData['website_id'], 'Customer Website is not matching');
        $this->assertEquals('customer@example.com', $customerData['email'], 'Customer email is not matching');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->segment->delete(); // should be replaced after implementation of segment repository
    }

    /**
     * Get POST data emulating segment with following condition:
     * – If Product was ordered and matches ALL of these Conditions:
     * -- Period equals or less than 365 Days Up To Date
     * -- Product Category is 2
     *
     * @return array
     */
    protected function getSegmentPostData()
    {
        // @codingStandardsIgnoreStart
        return [
            'name' => 'gfsdfgsd',
            'description' => '',
            'website_ids' => ['1'],
            'is_active' => '1',
            'conditions' => [
                '1' => [
                    'type' => 'Magento\\CustomerSegment\\Model\\Segment\\Condition\\Combine\\Root',
                    'aggregator' => 'all',
                    'value' => '1',
                    'new_child' => '',
                ],
                '1--1' => [
                    'type' => 'Magento\\CustomerSegment\\Model\\Segment\\Condition\\Product\\Combine\\History',
                    'operator' => '==',
                    'value' => 'ordered_history',
                    'aggregator' => 'all',
                    'new_child' => '',
                ],
                '1--1--1' => [
                    'type' => 'Magento\\CustomerSegment\\Model\\Segment\\Condition\\Uptodate',
                    'operator' => '>=',
                    'value' => '365',
                ],
                '1--1--2' => [
                    'type' => 'Magento\\CustomerSegment\\Model\\Segment\\Condition\\Product\\Attributes',
                    'attribute' => 'category_ids',
                    'operator' => '==',
                    'value' => '2',
                ],
            ],
        ];
        // @codingStandardsIgnoreEnd
    }
}
