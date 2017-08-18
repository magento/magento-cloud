<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CheckoutStaging\Api\Data;

use Magento\Checkout\Api\Data\PaymentDetailsInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\Staging\Model\VersionManager;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class ShippingInformationManagementTest
 */
class ShippingInformationManagementTest extends WebapiAbstract
{
    const SERVICE_VERSION = 'V1';
    const SERVICE_NAME = 'checkoutShippingInformationManagementV1';
    const RESOURCE_PATH = '/V1/carts/%s/shipping-information';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * Only active offline payments is available on checkout
     * @see \Magento\PaymentStaging\Plugin\Model\Method\PaymentMethodIsAvailable
     * @magentoApiDataFixture Magento/CheckoutStaging/_files/quote_with_check_payment.php
     */
    public function testSaveAddressInformationPaymentStagingOn()
    {
        if (TESTS_WEB_API_ADAPTER == self::ADAPTER_SOAP) {
            $this->markTestSkipped('Preview version should not works for SOAP Api');
        }

        /** @var Quote  $quote */
        $quote = $this->objectManager->create(Quote::class)
            ->load('test_order_1', 'reserved_order_id');

        $serviceInfo = [
            'soap' => [
                'service' => self::SERVICE_NAME,
                'operation' => self::SERVICE_NAME . 'saveAddressInformation',
                'serviceVersion' => self::SERVICE_VERSION,
            ],
            'rest' => [
                'resourcePath' => sprintf(self::RESOURCE_PATH, $quote->getId()),
                'httpMethod' => Request::HTTP_METHOD_POST,
            ],
        ];

        $requestData = [
            VersionManager::PARAM_NAME => STAGING_UPDATE_FIXTURE_ID,
            'address_information' => [
                'shipping_address' => $this->getShippingAddressData($quote),
                'billing_address' => $this->getBillingAddressData($quote),
                'shipping_method_code' => 'flatrate',
                'shipping_carrier_code' => 'flatrate',
            ]
        ];

        $expected = [
            'code' => 'checkmo',
            'title' => 'Check / Money order'
        ];
        $result = $this->_webApiCall($serviceInfo, $requestData);

        $this->assertEquals(1, count($result[PaymentDetailsInterface::PAYMENT_METHODS]));
        $this->assertEquals($expected, current($result[PaymentDetailsInterface::PAYMENT_METHODS]));
    }

    /**
     * @param Quote $quote
     * @return array
     */
    private function getShippingAddressData(Quote $quote)
    {
        return $this->getAddressData($quote->getShippingAddress());
    }

    /**
     * @param Quote $quote
     * @return array
     */
    private function getBillingAddressData(Quote $quote)
    {
        return $this->getAddressData($quote->getBillingAddress());
    }

    /**
     * @param Address $address
     * @return array
     */
    private function getAddressData(Address $address)
    {
        $addressData = [];
        $addressKeys = [
            'city', 'company', 'countryId', 'firstname', 'lastname', 'postcode',
            'region', 'regionCode', 'regionId', 'saveInAddressBook', 'street', 'telephone', 'email'
        ];

        foreach ($addressKeys as $key) {
            $method = 'get' . $key;
            $addressData[$key] = $address->$method();
        }

        return $addressData;
    }
}
