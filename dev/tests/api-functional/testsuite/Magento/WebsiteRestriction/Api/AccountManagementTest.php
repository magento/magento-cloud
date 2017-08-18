<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\WebsiteRestriction\Api;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Webapi\Exception as HTTPExceptionCodes;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\Helper\Customer as CustomerHelper;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Test class for Magento\WebsiteRestriction\Plugin\Model\AccountManagement.
 */
class AccountManagementTest extends WebapiAbstract
{
    /**
     * Customer helper.
     *
     * @var CustomerHelper
     */
    private $customerHelper;

    /**
     * Data object processor.
     *
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * Execute per test initialization.
     */
    public function setUp()
    {
        $this->customerHelper = new CustomerHelper();

        $this->dataObjectProcessor = Bootstrap::getObjectManager()->create(
            \Magento\Framework\Reflection\DataObjectProcessor::class
        );
    }

    /**
     * Test that we can not register customer via API when it is restricted.
     *
     * @magentoApiDataFixture Magento/WebsiteRestriction/_files/config_enable_restriction.php
     */
    public function testCreateCustomerWithRestrictions()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => \Magento\Customer\Api\AccountManagementTest::RESOURCE_PATH,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => \Magento\Customer\Api\AccountManagementTest::SERVICE_NAME,
                'serviceVersion' => \Magento\Customer\Api\AccountManagementTest::SERVICE_VERSION,
                'operation' => \Magento\Customer\Api\AccountManagementTest::SERVICE_NAME . 'CreateAccount',
            ],
        ];

        $customerDataArray = $this->dataObjectProcessor->buildOutputDataArray(
            $this->customerHelper->createSampleCustomerDataObject(),
            \Magento\Customer\Api\Data\CustomerInterface::class
        );

        $requestData = ['customer' => $customerDataArray, 'password' => CustomerHelper::PASSWORD];

        try {
            $this->_webApiCall($serviceInfo, $requestData);
            $this->fail('Expected exception did not occur.');
        } catch (\Exception $e) {
            if (TESTS_WEB_API_ADAPTER == self::ADAPTER_SOAP) {
                $expectedException = new InputException();
                $expectedException->addError(__('Can not register new customer due to restrictions are enabled.'));

                $this->assertInstanceOf('SoapFault', $e);

                $this->checkSoapFault(
                    $e,
                    $expectedException->getRawMessage(),
                    'env:Sender',
                    $expectedException->getParameters() // expected error parameters
                );
            } else {
                $this->assertEquals(HTTPExceptionCodes::HTTP_BAD_REQUEST, $e->getCode());

                $exceptionData = $this->processRestExceptionResult($e);
                $expectedExceptionData = [
                    'message' => 'Can not register new customer due to restrictions are enabled.',
                ];

                $this->assertEquals($expectedExceptionData, $exceptionData);
            }
        }
    }
}
