<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCard\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\TestFramework\TestCase\WebapiAbstract;

class ProductRepositoryInterfaceTest extends WebapiAbstract
{
    const SERVICE_NAME = 'catalogProductRepositoryV1';
    const SERVICE_VERSION = 'V1';
    const RESOURCE_PATH = '/V1/products';

    /**
     * Update Product
     *
     * @param string $sku
     * @return mixed
     */
    protected function getProduct($sku)
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/' . $sku,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'Get',
            ],
        ];

        $response = $this->_webApiCall($serviceInfo, ['sku' => $sku]);
        return $response;
    }

    /**
     * Save Product
     *
     * @param ProductInterface $product
     * @return mixed
     */
    protected function saveProduct($product)
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'Save',
            ],
        ];
        $requestData = ['product' => $product];
        return $this->_webApiCall($serviceInfo, $requestData);
    }

    /**
     * Delete Product
     *
     * @param string $sku
     * @return boolean
     */
    protected function deleteProduct($sku)
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/' . $sku,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_DELETE,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'DeleteById',
            ],
        ];

        return (TESTS_WEB_API_ADAPTER == self::ADAPTER_SOAP) ?
            $this->_webApiCall($serviceInfo, ['sku' => $sku]) : $this->_webApiCall($serviceInfo);
    }

    /**
     *
     */
    public function testGiftCard()
    {
        // Create a gift card product with default info
        $giftCardAmountData = [
            "attribute_id" => 1,
            "website_id" => "0",
            "value" => 100.12,
            "website_value" => 100.12
        ];

        $productData = [
            ProductInterface::SKU => 'Gift_Card_Product_500',
            ProductInterface::NAME => 'Gift Card Product 500',
            ProductInterface::VISIBILITY => 4,
            ProductInterface::TYPE_ID => 'giftcard',
            ProductInterface::PRICE => 100.12,
            ProductInterface::STATUS => 1,
            ProductInterface::ATTRIBUTE_SET_ID => 4,
            ProductInterface::EXTENSION_ATTRIBUTES_KEY => [
                'giftcard_amounts' => [$giftCardAmountData]
            ],
            'custom_attributes' => [
                ['attribute_code' => 'description', 'value' => 'Description'],
                ['attribute_code' => 'giftcard_type', 'value' => 0],
            ]
        ];

        $this->saveProduct($productData);
        $response = $this->getProduct($productData[ProductInterface::SKU]);
        $this->assertArrayHasKey('custom_attributes', $response);
        $customAttributes = $response['custom_attributes'];
        $this->stepCustomAttributeChecks(
            $customAttributes,
            $giftCardAmountData
        );

        // Update a gift card product
        $giftCardAmountData = [
            "attribute_id" => 1,
            "website_id" => "0",
            "value" => 374.89,
            "website_value" => 374.89
        ];

        $productData = [
            ProductInterface::SKU => 'Gift_Card_Product_500',
            ProductInterface::NAME => 'Gift Card Product 500',
            ProductInterface::VISIBILITY => 4,
            ProductInterface::TYPE_ID => 'giftcard',
            ProductInterface::PRICE => 374.89,
            ProductInterface::STATUS => 1,
            ProductInterface::ATTRIBUTE_SET_ID => 4,
            ProductInterface::EXTENSION_ATTRIBUTES_KEY => [
                'giftcard_amounts' => [$giftCardAmountData]
            ],
            'custom_attributes' => [
                ['attribute_code' => 'description', 'value' => 'Description'],
                ['attribute_code' => 'giftcard_type', 'value' => 0],
            ]
        ];

        $this->saveProduct($productData);
        $response = $this->getProduct($productData[ProductInterface::SKU]);
        $this->assertArrayHasKey('custom_attributes', $response);
        $customAttributes = $response['custom_attributes'];
        $this->stepCustomAttributeChecks(
            $customAttributes,
            $giftCardAmountData
        );

        // Delete a gift card product
        $response = $this->deleteProduct($productData[ProductInterface::SKU]);
        $this->assertTrue($response);
    }

    /**
     * @param mixed $customAttributes
     * @param mixed $giftCardAmountData
     * @return void
     */
    protected function stepCustomAttributeChecks($customAttributes, $giftCardAmountData)
    {
        $this->assertNotNull(
            $customAttributes,
            "CREATE: expected to have custom attributes"
        );
        $hasAmounts = false;
        foreach ($customAttributes as $customAttribute) {
            if ($customAttribute['attribute_code'] == 'giftcard_amounts') {
                $hasAmounts = true;
                $this->assertEquals(
                    $giftCardAmountData['website_id'],
                    $customAttribute['value'][0]['website_id']
                );
                $this->assertEquals(
                    $giftCardAmountData['value'],
                    $customAttribute['value'][0]['value']
                );
                $this->assertEquals(
                    $giftCardAmountData['website_value'],
                    $customAttribute['value'][0]['website_value']
                );
            }
        }
        $this->assertNotFalse($hasAmounts);
    }
}
