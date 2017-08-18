<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CheckoutStaging\Api;

use Magento\Framework\Webapi\Rest\Request;
use Magento\Quote\Model\Quote;
use Magento\Staging\Model\VersionManager;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class PaymentInformationManagementTest
 */
class PaymentInformationManagementTest extends WebapiAbstract
{
    const SERVICE_VERSION = 'V1';
    const SERVICE_NAME = 'checkoutPaymentInformationManagementV1';
    const RESOURCE_PATH = '/V1/carts/mine/payment-information';

    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * @magentoApiDataFixture Magento/CheckoutStaging/_files/quote_with_check_payment.php
     * @expectedException \Exception
     * @expectedExceptionMessage Preview mode doesn't allow submitting the order.
     */
    public function testSavePaymentInformationAndPlaceOrderWithException()
    {
        if (TESTS_WEB_API_ADAPTER == self::ADAPTER_SOAP) {
            $this->markTestSkipped('Preview version should not works for SOAP Api');
        }

        /** @var Quote $quote */
        $quote = $this->objectManager->create(Quote::class)
            ->load('test_order_1', 'reserved_order_id');
        $cartId = $quote->getId();

        $serviceInfo = [
            'soap' => [
                'service' => self::SERVICE_NAME,
                'operation' => self::SERVICE_NAME . 'savePaymentInformationAndPlaceOrder',
                'serviceVersion' => self::SERVICE_VERSION,
            ],
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => Request::HTTP_METHOD_POST,
            ],
        ];
        $payment = $quote->getPayment();
        $address = $quote->getBillingAddress();
        $addressData = [];
        $keys = [
            'city', 'countryId', 'customerAddressId', 'customerId', 'firstname', 'lastname', 'postcode',
            'region', 'regionCode', 'regionId', 'saveInAddressBook', 'street', 'telephone', 'email'
        ];
        foreach ($keys as $key) {
            $method = 'get' . $key;
            $addressData[$key] = $address->$method();
        }
        $requestData = [
            VersionManager::PARAM_NAME => STAGING_UPDATE_FIXTURE_ID,
            'cart_id' => $cartId,
            'billingAddress' => $addressData,
            'paymentMethod' => [
                'additional_data' => $payment->getAdditionalData(),
                'method' => $payment->getMethod(),
                'po_number' => $payment->getPoNumber()
            ]
        ];
        $this->_webApiCall($serviceInfo, $requestData);
    }
}
