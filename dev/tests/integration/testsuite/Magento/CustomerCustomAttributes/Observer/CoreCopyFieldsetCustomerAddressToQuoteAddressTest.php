<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerCustomAttributes\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Quote\Model\Quote;

class CoreCopyFieldsetCustomerAddressToQuoteAddressTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CoreCopyFieldsetCustomerAccountToQuote
     */
    private $model;

    /**
     * @var Event
     */
    private $event;

    /**
     * @var Observer
     */
    private $observer;

    /**
     * @var DataObject
     */
    private $source;

    /**
     * @var Quote
     */
    private $target;

    protected function setUp()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->event = $objectManager->create(Event::class);
        $this->target = $objectManager->create(Quote::class);
        /** @var Observer $observer */
        $this->observer = $objectManager->create(Observer::class);

        $this->event->setData('target', $this->target);
        $this->observer->setEvent($this->event);

        $this->model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            CoreCopyFieldsetCustomerAccountToQuote::class
        );
    }

    /**
     * @dataProvider sourceDataProvider
     */
    public function testExecute(array $source, array $target)
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->source = $objectManager->create(DataObject::class);

        $this->event->setData('source', $this->source);
        $this->source->setData($source);
        $this->target->setData($target);

        $this->assertSame($this->model, $this->model->execute($this->observer));
    }

    /**
     * @dataProvider sourceDataProvider
     */
    public function testExecuteWithSourceAsArray(array $source, array $target)
    {
        $this->event->setData('source', $source);
        $this->target->setData($target);

        $this->assertSame($this->model, $this->model->execute($this->observer));
    }

    /**
     * @return array
     */
    public function sourceDataProvider()
    {
        return [
            [
                [
                    'id' => 1,
                    'customer_id' => 42,
                    'country_id' => 'US',
                    'telephone' => '20512345678',
                    'postcode' => '01100',
                    'city' => 'New York',
                    'firstname' => 'John',
                    'lastname' => 'Doe',
                    'default_shipping' => true,
                    'default_billing' => true,
                    'domofon' => '123',
                    'region_code' => 'NY',
                    'region' => 'New York'
                ],
                [
                    'entity_id' => '2',
                    'store_id' => 1,
                    'customer_id' => 2,
                    'grad_total' => '99.99'
                ]
            ]
        ];
    }
}
