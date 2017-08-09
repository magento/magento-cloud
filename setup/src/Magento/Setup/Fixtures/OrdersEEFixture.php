<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Setup\Fixtures;

/**
 * Class OrdersEEFixture
 *
 * Updates order entities existing in the database to be compliant with CustomerCustomAttributes module features.
 */
class OrdersEEFixture extends Fixture
{
    /**
     * @var int
     */
    protected $priority = 136;

    /**
     * Order resource.
     *
     * @var \Magento\Sales\Model\ResourceModel\Order
     */
    private $order;

    /**
     * Order Address resource.
     *
     * @var \Magento\Sales\Model\ResourceModel\Order\Address
     */
    private $orderAddress;

    /**
     * Order Customer Custom Attributes resource.
     *
     * @var \Magento\CustomerCustomAttributes\Model\ResourceModel\Sales\Order
     */
    private $orderCustomAttributes;

    /**
     * Order Address Customer Custom Attributes resource.
     *
     * @var \Magento\CustomerCustomAttributes\Model\ResourceModel\Sales\Order\Address
     */
    private $orderAddressCustomAttributes;

    /**
     * OrdersEEFixture constructor.
     * @param FixtureModel $fixtureModel
     * @param \Magento\Sales\Model\ResourceModel\Order $order
     * @param \Magento\Sales\Model\ResourceModel\Order\Address $orderAddress
     * @param \Magento\CustomerCustomAttributes\Model\ResourceModel\Sales\Order $orderCustomAttributes
     * @param \Magento\CustomerCustomAttributes\Model\ResourceModel\Sales\Order\Address $orderAddressCustomAttributes
     */
    public function __construct(
        FixtureModel $fixtureModel,
        \Magento\Sales\Model\ResourceModel\Order $order,
        \Magento\Sales\Model\ResourceModel\Order\Address $orderAddress,
        \Magento\CustomerCustomAttributes\Model\ResourceModel\Sales\Order $orderCustomAttributes,
        \Magento\CustomerCustomAttributes\Model\ResourceModel\Sales\Order\Address $orderAddressCustomAttributes
    ) {
        $this->order = $order;
        $this->orderAddress = $orderAddress;
        $this->orderCustomAttributes = $orderCustomAttributes;
        $this->orderAddressCustomAttributes = $orderAddressCustomAttributes;
        parent::__construct($fixtureModel);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $orderTable = $this->order->getTable('sales_order');
        $orderAddressTable = $this->order->getTable('sales_order_address');
        $flatOrderTable = $this->orderCustomAttributes->getTable('magento_customercustomattributes_sales_flat_order');
        $flatOrderAddressTable = $this->orderAddressCustomAttributes->getTable(
            'magento_customercustomattributes_sales_flat_order_address'
        );

        $this->orderCustomAttributes->getConnection()->query(
            "INSERT INTO `{$flatOrderTable}` (`entity_id`) SELECT `entity_id` FROM `{$orderTable}` 
            WHERE `entity_id` > (SELECT COUNT(`entity_id`) FROM `{$flatOrderTable}`);"
        );
        $this->orderAddressCustomAttributes->getConnection()->query(
            "INSERT INTO `{$flatOrderAddressTable}` (`entity_id`) SELECT `entity_id` FROM `{$orderAddressTable}` 
            WHERE `entity_id` > (SELECT COUNT(`entity_id`) FROM `{$flatOrderAddressTable}`);"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getActionTitle()
    {
        return 'Updating EE orders';
    }

    /**
     * {@inheritdoc}
     */
    public function introduceParamLabels()
    {
        return [
            'orders_ee' => 'EE orders'
        ];
    }
}
