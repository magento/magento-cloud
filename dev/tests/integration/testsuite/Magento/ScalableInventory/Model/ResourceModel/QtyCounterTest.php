<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\ScalableInventory\Model\ResourceModel;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Amqp\Config;
use Magento\Framework\Amqp\Exchange;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\MessageQueue\ExchangeFactory;
use Magento\MysqlMq\Model\Message;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class QtyCounterTest @covers \Magento\ScalableInventory\Model\ResourceModel\QtyCounter
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class QtyCounterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Help place order to invoke \Magento\ScalableInventory\Model\ResourceModel\QtyCounter::correctItemsQty().
     *
     * @var CartManagementInterface
     */
    private $cartManagement;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->cartManagement = Bootstrap::getObjectManager()->get(CartManagementInterface::class);
    }

    /**
     * Check Amqp publisher publish message when Amqp is configured.
     *
     * @magentoDataFixture Magento/ScalableInventory/_files/enable_backorders.php
     * @magentoDataFixture Magento/Catalog/_files/products.php
     * @magentoDataFixture Magento/Customer/_files/customer_sample.php
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @return void
     */
    public function testCorrectItemsQtyWithConfiguredAmqp()
    {
        $this->setAmqpConfiguredStatus(true);
        $this->mockAmqpExchange();
        /** @var PaymentInterface $payment */
        $payment = Bootstrap::getObjectManager()->get(PaymentInterface::class);
        $payment->setMethod('checkmo');
        $cartId = $this->getCartId();
        $this->cartManagement->placeOrder($cartId, $payment);
    }

    /**
     * Check there is no exception when Amqp is not configured, and message added to Mysql queue.
     *
     * @magentoDataFixture Magento/ScalableInventory/_files/enable_backorders.php
     * @magentoDataFixture Magento/Catalog/_files/products.php
     * @magentoDataFixture Magento/Customer/_files/customer_sample.php
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @return void
     */
    public function testCorrectItemsQtyWithoutConfiguredAmqp()
    {
        $this->setAmqpConfiguredStatus(false);
        /** @var PaymentInterface $payment */
        $payment = Bootstrap::getObjectManager()->get(PaymentInterface::class);
        $payment->setMethod('checkmo');
        $cartId = $this->getCartId();
        $this->cartManagement->placeOrder($cartId, $payment);
        /** @var Message $message */
        $message = Bootstrap::getObjectManager()->get(Message::class);
        $message->load(QtyCounter::TOPIC_NAME, 'topic_name');
        $messageBody = sprintf(
            '{"items":[{"product_id":%s,"qty":1}],"website_id":0,"operator":"-"}',
            $this->getProduct()->getId()
        );
        self::assertEquals($messageBody, $message->getBody());
    }

    /**
     * Mock AMQP Exchange class to check Exchange::enqueue() was called with correct input data.
     */
    private function mockAmqpExchange()
    {
        /** @var Exchange|\PHPUnit_Framework_MockObject_MockObject $amqpExchange */
        $amqpExchange = $this->getMockBuilder(Exchange::class)
            ->disableOriginalConstructor()
            ->getMock();
        $amqpExchange->expects(self::once())
            ->method('enqueue')
            ->with(
                QtyCounter::TOPIC_NAME,
                self::callback(function ($data) {
                    $messageBody = sprintf(
                        '{"items":[{"product_id":%s,"qty":1}],"website_id":0,"operator":"-"}',
                        $this->getProduct()->getId()
                    );

                    return $data->getBody() === $messageBody ? true : false;
                })
            )
            ->willReturn(null);
        /** @var ExchangeFactory|\PHPUnit_Framework_MockObject_MockObject $exchangeFactory */
        $exchangeFactory = $this->getMockBuilder(ExchangeFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $exchangeFactory->method('create')
            ->willReturn($amqpExchange);
        Bootstrap::getObjectManager()->configure([ExchangeFactory::class => ['shared' => true]]);
        Bootstrap::getObjectManager()->addSharedInstance($exchangeFactory, ExchangeFactory::class);
    }

    /**
     * Mock AMQP configuration.
     *
     * @param bool $enabled
     * @return void
     */
    private function setAmqpConfiguredStatus($enabled)
    {
        if ($enabled) {
            $data = [
                'amqp' =>
                    [
                        'host' => 'localhost',
                        'port' => '5672',
                        'user' => 'guest',
                        'password' => 'guest',
                        'virtualhost' => '/',
                        'ssl' => '',
                    ],
            ];
        } else {
            $data = [
                'amqp' =>
                    [
                        'host' => '',
                        'port' => '',
                        'user' => '',
                        'password' => '',
                        'virtualhost' => '/',
                        'ssl' => '',
                    ],
            ];
        }

        /** @var DeploymentConfig|\PHPUnit_Framework_MockObject_MockObject $deploymentConfig */
        $deploymentConfig = $this->getMockBuilder(DeploymentConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deploymentConfig->method('getConfigData')->willReturn($data);

        $amqpConfig = Bootstrap::getObjectManager()->create(Config::class, ['config' => $deploymentConfig]);

        Bootstrap::getObjectManager()->configure([Config::class => ['shared' => true]]);
        Bootstrap::getObjectManager()->addSharedInstance($amqpConfig, Config::class);
    }

    /**
     * Build test quote and return it's Id for placing order.
     *
     * @return string
     */
    private function getCartId()
    {
        $product = $this->getProduct();
        $address = $this->getShippingAddress();
        $customer = $this->getCustomer();
        /** @var Quote $quote */
        $quote = Bootstrap::getObjectManager()->create(Quote::class);
        $quote->setStoreId(1)
            ->setIsActive(true)
            ->setIsMultiShipping(false)
            ->setReservedOrderId('test_order_with_simple_product_without_address')
            ->addProduct($product->load($product->getId()), 1);
        $quote->setShippingAddress($address);
        $quote->setBillingAddress($address);
        $quote->getPayment()->setMethod('checkmo');
        $quote->getShippingAddress()->setShippingMethod('flatrate_flatrate');
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->getShippingAddress()->collectShippingRates();
        $quote->setCustomer($customer);
        $quote->collectTotals()->save();

        /** @var QuoteIdMask $quoteIdMask */
        $quoteIdMask = Bootstrap::getObjectManager()->create(QuoteIdMaskFactory::class)->create();
        $quoteIdMask->setQuoteId($quote->getId());
        $quoteIdMask->setDataChanges(true);
        $quoteIdMask->save();

        return $quote->getId();
    }

    /**
     * Build test product for quote.
     *
     * @return Product
     */
    private function getProduct()
    {
        /** @var ProductRepository $productRepository */
        $productRepository = Bootstrap::getObjectManager()->get(ProductRepository::class);

        return $productRepository->get('simple');
    }

    /**
     * Build quote shipping address with test data.
     *
     * @return Address
     */
    private function getShippingAddress()
    {
        /** @var Address $address */
        $address = Bootstrap::getObjectManager()->get(AddressInterface::class);
        $address->setRegionId(12);
        $address->setCity('test_city');
        $address->setStreet('test_street');
        $address->setCountryId('US');
        $address->setTelephone('555-555-55-55');
        $address->setFirstname('John');
        $address->setLastname('Doe');
        $address->setPostcode('90230');
        $address->setEmail('john.doe@example.com');

        return $address;
    }

    /**
     * Load test customer for quote.
     *
     * @return CustomerInterface
     */
    private function getCustomer()
    {
        /** @var CustomerRepository $customerRepository */
        $customerRepository = Bootstrap::getObjectManager()->get(CustomerRepository::class);

        return $customerRepository->get('customer@example.com');
    }
}
