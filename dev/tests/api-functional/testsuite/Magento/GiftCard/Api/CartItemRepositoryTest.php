<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCard\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

class CartItemRepositoryTest extends WebapiAbstract
{
    const SERVICE_VERSION = 'V1';
    const SERVICE_NAME = 'quoteCartItemRepositoryV1';
    const RESOURCE_PATH = '/V1/carts/';

    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
    }

    /**
     * @magentoApiDataFixture Magento/GiftCard/_files/quote_with_items_saved.php
     */
    public function testGetList()
    {
        /** @var \Magento\Quote\Model\Quote  $quote */
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_item_with_gift_card_items', 'reserved_order_id');
        $cartId = $quote->getId();
        $output = [];
        /** @var  \Magento\Quote\Api\Data\CartItemInterface $item */
        foreach ($quote->getAllItems() as $item) {
            $data = [
                'item_id' => $item->getItemId(),
                'sku' => $item->getSku(),
                'qty' => $item->getQty(),
                'name' => $item->getName(),
                'price' => intval($item->getPrice()),
                'product_type' => $item->getProductType(),
                'quote_id' => $item->getQuoteId(),
            ];
            $options = $item->getOptions();
            if (is_array($options)) {
                $optionsArray = [];
                foreach ($options as $option) {
                    $optionsArray[$option->getCode()] = $option->getValue();
                }
                $productOptions = [
                    'extension_attributes' => [
                        'giftcard_item_option' => [
                            'giftcard_amount' =>
                                $this->getGiftCardOptionValue('giftcard_amount', $optionsArray),
                            'giftcard_sender_name' =>
                                $this->getGiftCardOptionValue('giftcard_sender_name', $optionsArray),
                            'giftcard_recipient_name' =>
                                $this->getGiftCardOptionValue('giftcard_recipient_name', $optionsArray),
                            'giftcard_sender_email' =>
                                $this->getGiftCardOptionValue('giftcard_sender_email', $optionsArray),
                            'giftcard_recipient_email' =>
                                $this->getGiftCardOptionValue('giftcard_recipient_email', $optionsArray),
                            'giftcard_message' =>
                                $this->getGiftCardOptionValue('giftcard_message', $optionsArray),
                        ]
                    ]
                ];
                $data['product_option'] = $productOptions;
            }

            $output[] = $data;
        }
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . $cartId . '/items',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'GetList',
            ],
        ];

        $requestData = ["cartId" => $cartId];
        $this->assertEquals($output, $this->_webApiCall($serviceInfo, $requestData));
    }

    /**
     * @param string $key
     * @param array $optionsArray
     * @return string|float|null
     */
    protected function getGiftCardOptionValue($key, $optionsArray)
    {
        return array_key_exists($key, $optionsArray) ? $optionsArray[$key] : null;
    }

    /**
     * @magentoApiDataFixture Magento/GiftCard/_files/quote_with_items_saved.php
     * @magentoApiDataFixture Magento/GiftCard/_files/gift_card.php
     */
    public function testAddItem()
    {
        /** @var  \Magento\Catalog\Model\Product $product */
        $product = $this->objectManager->create(\Magento\Catalog\Model\Product::class);
        $productId = $product->getIdBySku('gift-card');
        $product->load($productId);
        $productSku = $product->getSku();
        /** @var \Magento\Quote\Model\Quote  $quote */
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_item_with_gift_card_items', 'reserved_order_id');
        $cartId = $quote->getId();
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . $cartId . '/items',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'Save',
            ],
        ];

        $requestData = [
            "cartItem" => [
                "sku" => $productSku,
                "qty" => 7,
                "quote_id" => $cartId,
                "product_option" => [
                    "extension_attributes" => [
                        "giftcard_item_option" => [
                            'giftcard_amount' => 'custom',
                            'custom_giftcard_amount' => 15,
                            "giftcard_sender_name" => "test test",
                            "giftcard_sender_email" => "testtest@example.com",
                            "giftcard_recipient_name" => "recipient test",
                            "giftcard_recipient_email" => "recipienttest@example.com",
                        ]
                    ]
                ]
            ],
        ];
        /** @var \Magento\Quote\Api\Data\CartItemInterface $item */
        $item = $this->_webApiCall($serviceInfo, $requestData);
        $this->assertTrue($quote->hasProductId($productId));
        $this->assertEquals(7, $quote->getItemById($item['item_id'])->getQty());
    }

    /**
     * @magentoApiDataFixture Magento/GiftCard/_files/quote_with_items_saved.php
     * @magentoApiDataFixture Magento/GiftCard/_files/gift_card.php
     */
    public function testRemoveItem()
    {
        /** @var \Magento\Quote\Model\Quote  $quote */
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_item_with_gift_card_items', 'reserved_order_id');
        $cartId = $quote->getId();
        $product = $this->objectManager->create(\Magento\Catalog\Model\Product::class);
        $productId = $product->getIdBySku('gift-card-with-allowed-messages');
        $product->load($productId);
        $items = $quote->getAllItems();
        $itemId = $items[0]->getId();
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . $cartId . '/items/' . $itemId,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_DELETE,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'DeleteById',
            ],
        ];

        $requestData = [
            "cartId" => $cartId,
            "itemId" => $itemId,
        ];
        $this->assertTrue($this->_webApiCall($serviceInfo, $requestData));
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_item_with_gift_card_items', 'reserved_order_id');
        $this->assertFalse($quote->hasProductId($productId));
    }

    /**
     * @magentoApiDataFixture Magento/GiftCard/_files/quote_with_items_saved.php
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testUpdateItemAmount()
    {
        /** @var \Magento\Quote\Model\Quote  $quote */
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_item_with_gift_card_items', 'reserved_order_id');
        $cartId = $quote->getId();
        $product = $this->objectManager->create(\Magento\Catalog\Model\Product::class);
        $productId = $product->getIdBySku('gift-card-with-allowed-messages');
        $product->load($productId);
        $items = $quote->getAllItems();
        $itemsQty = count($items);
        $itemId = $items[0]->getId();
        $output = [];
        /** @var  \Magento\Quote\Api\Data\CartItemInterface $item */
        foreach ($quote->getAllItems() as $item) {
            $data = [
                'sku' => $item->getSku(),
                'qty' => 5,
                'name' => $item->getName(),
                'price' => 10,
                'product_type' => $item->getProductType(),
                'quote_id' => $item->getQuoteId(),
            ];
            $options = $item->getOptions();
            if (is_array($options)) {
                $optionsArray = [];
                foreach ($options as $option) {
                    $optionsArray[$option->getCode()] = $option->getValue();
                }
                $productOptions = [
                    'extension_attributes' => [
                        'giftcard_item_option' => [
                            'giftcard_amount' => 10,
                            'giftcard_sender_name' =>
                                $this->getGiftCardOptionValue('giftcard_sender_name', $optionsArray),
                            'giftcard_recipient_name' =>
                                $this->getGiftCardOptionValue('giftcard_recipient_name', $optionsArray),
                            'giftcard_sender_email' =>
                                $this->getGiftCardOptionValue('giftcard_sender_email', $optionsArray),
                            'giftcard_recipient_email' =>
                                $this->getGiftCardOptionValue('giftcard_recipient_email', $optionsArray),
                            'giftcard_message' => "my_new_message",
                        ]
                    ]
                ];
                $data['product_option'] = $productOptions;
            }

            $output[] = $data;
        }
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . $cartId . '/items/' . $itemId,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_PUT,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'Save',
            ],
        ];

        if (TESTS_WEB_API_ADAPTER == self::ADAPTER_SOAP) {
            $requestData = [
                "cartItem" => [
                    "qty" => 5,
                    "quote_id" => $cartId,
                    "itemId" => $itemId,
                    "product_option" => [
                        "extension_attributes" => [
                            'giftcard_item_option' => [
                                'giftcard_amount' => 'custom',
                                'custom_giftcard_amount' => 10,
                                'giftcard_sender_name' => 'test sender name',
                                'giftcard_recipient_name' => 'test recipient name',
                                'giftcard_sender_email' => 'sender@example.com',
                                'giftcard_recipient_email' => 'recipient@example.com',
                                'giftcard_message' => "my_new_message",
                            ]
                        ]
                    ]
                ],
            ];
        } else {
            $requestData = [
                "cartItem" => [
                    "qty" => 5,
                    "quote_id" => $cartId,
                    "product_option" => [
                        "extension_attributes" => [
                            'giftcard_item_option' => [
                                'giftcard_amount' => 'custom',
                                'custom_giftcard_amount' => 10,
                                'giftcard_sender_name' => 'test sender name',
                                'giftcard_recipient_name' => 'test recipient name',
                                'giftcard_sender_email' => 'sender@example.com',
                                'giftcard_recipient_email' => 'recipient@example.com',
                                'giftcard_message' => "my_new_message",
                            ]
                        ]
                    ]
                ],
            ];
        }
        $updatedItem = $this->_webApiCall($serviceInfo, $requestData);
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_item_with_gift_card_items', 'reserved_order_id');
        $this->assertTrue($quote->hasProductId($productId));
        $items = $quote->getAllItems();
        $itemsCountAfterUpdate = count($items);
        $itemId = $items[0]->getId();
        $this->assertEquals(5, $updatedItem['qty']);
        $this->assertEquals($itemId, $updatedItem['item_id']);
        $this->assertEquals($itemsCountAfterUpdate, $itemsQty);
        unset($updatedItem['item_id']);
        $this->assertEquals($output[0], $updatedItem);
    }

    /**
     * @magentoApiDataFixture Magento/GiftCard/_files/quote_with_items_saved.php
     */
    public function testUpdateItemQty()
    {
        /** @var \Magento\Quote\Model\Quote  $quote */
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_item_with_gift_card_items', 'reserved_order_id');
        $cartId = $quote->getId();
        $product = $this->objectManager->create(\Magento\Catalog\Model\Product::class);
        $productId = $product->getIdBySku('gift-card-with-allowed-messages');
        $product->load($productId);
        $items = $quote->getAllItems();
        $itemsQty = count($items);
        $itemId = $items[0]->getId();
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . $cartId . '/items/' . $itemId,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_PUT,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'Save',
            ],
        ];

        if (TESTS_WEB_API_ADAPTER == self::ADAPTER_SOAP) {
            $requestData = [
                "cartItem" => [
                    "qty" => 5,
                    "quote_id" => $cartId,
                    "itemId" => $itemId,
                ],
            ];
        } else {
            $requestData = [
                "cartItem" => [
                    "quote_id" => $cartId,
                    "qty" => 5,
                ],
            ];
        }
        $updatedItem = $this->_webApiCall($serviceInfo, $requestData);
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_item_with_gift_card_items', 'reserved_order_id');
        $this->assertTrue($quote->hasProductId($productId));
        $items = $quote->getAllItems();
        $itemsCountAfterUpdate = count($items);
        $itemId = $items[0]->getId();
        $this->assertEquals(5, $updatedItem['qty']);
        $this->assertEquals($itemId, $updatedItem['item_id']);
        $this->assertEquals($itemsCountAfterUpdate, $itemsQty);
    }

    /**
     * @param array $giftCardOptionData
     * @param string $message
     * @magentoApiDataFixture Magento/GiftCard/_files/quote_with_items_saved.php
     * @magentoApiDataFixture Magento/GiftCard/_files/gift_card.php
     * @dataProvider addItemWithInvalidDataDataProvider
     */
    public function testAddItemWithInvalidData($giftCardOptionData, $message)
    {
        /** @var  \Magento\Catalog\Model\Product $product */
        $product = $this->objectManager->create(\Magento\Catalog\Model\Product::class);
        $productId = $product->getIdBySku('gift-card');
        $product->load($productId);
        $productSku = $product->getSku();
        /** @var \Magento\Quote\Model\Quote  $quote */
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_item_with_gift_card_items', 'reserved_order_id');
        $cartId = $quote->getId();
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . $cartId . '/items',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'Save',
            ],
        ];

        $requestData = [
            "cartItem" => [
                "sku" => $productSku,
                "qty" => 7,
                "quote_id" => $cartId,
                "product_option" => ["extension_attributes" => [
                    "giftcard_item_option" => $giftCardOptionData
                ]
                ]
            ]
        ];
        try {
            $this->_webApiCall($serviceInfo, $requestData);
        } catch (\Exception $e) {
            if (TESTS_WEB_API_ADAPTER == self::ADAPTER_SOAP) {
                $this->assertEquals($message, $e->getMessage());
            } else {
                $this->assertEquals('{"message":"' . $message . '"}', $e->getMessage());
            }
        }
    }

    public function addItemWithInvalidDataDataProvider()
    {
        return [
            'invalid_amount' => [
                [
                    'giftcard_amount' => 'custom',
                    'custom_giftcard_amount' => -15,
                    "giftcard_sender_name" => "test test",
                    "giftcard_sender_email" => "testtest@example.com",
                    "giftcard_recipient_name" => "recipient test",
                    "giftcard_recipient_email" => "recipienttest@example.com",

                ],
                'Please specify a gift card amount.'
            ],
            'empty_sender_name' => [
                [
                    'giftcard_amount' => 'custom',
                    'custom_giftcard_amount' => 10,
                    "giftcard_sender_name" => "",
                    "giftcard_sender_email" => "testtest@example.com",
                    "giftcard_recipient_name" => "recipient test",
                    "giftcard_recipient_email" => "recipienttest@example.com",
                ],
                'Please specify all the required information.'
            ],
            'invalid_sender_email' => [
                [
                    'giftcard_amount' => 'custom',
                    'custom_giftcard_amount' => 10,
                    "giftcard_sender_name" => "sender",
                    "giftcard_sender_email" => "test",
                    "giftcard_recipient_name" => "recipient test",
                    "giftcard_recipient_email" => "recipienttest@example.com",
                ],
                'Please specify all the required information.'
            ],
            'invalid_recipient_name' => [
                [
                    'giftcard_amount' => 'custom',
                    'custom_giftcard_amount' => 10,
                    "giftcard_sender_name" => "sender",
                    "giftcard_sender_email" => "testtest@example.com",
                    "giftcard_recipient_name" => "",
                    "giftcard_recipient_email" => "recipienttest@example.com",
                ],
                'Please specify all the required information.'
            ]
        ];
    }
}
