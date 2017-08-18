<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Rma\Controller;

/**
 * @magentoDbIsolation enabled
 */
class ReturnsTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    protected function setUp()
    {
        parent::setUp();
        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $this->_customerSession = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Customer\Model\Session::class,
            [$logger]
        );
        /** @var \Magento\Customer\Api\AccountManagementInterface $service */
        $service = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Customer\Api\AccountManagementInterface::class
        );
        $customer = $service->authenticate('customer@example.com', 'password');
        $this->_customerSession->setCustomerDataAsLoggedIn($customer);
    }

    protected function tearDown()
    {
        $this->_customerSession->logout();
        $this->_customerSession = null;
        parent::tearDown();
    }

    /**
     * @magentoConfigFixture current_store sales/magento_rma/enabled 1
     * @magentoDataFixture Magento/Rma/_files/rma.php
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @dataProvider isResponseContainDataProvider
     */
    public function testIsResponseContain($uri, $content)
    {
        /** @var $rma \Magento\Rma\Model\Rma */
        $rma = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Rma\Model\Rma::class);
        $rma->load(1, 'increment_id');
        $rma->setCustomerId($this->_customerSession->getCustomerId());
        $rma->save();

        $this->getRequest()->setParam('entity_id', $rma->getEntityId());

        $this->dispatch($uri);
        $this->assertContains($content, $this->getResponse()->getBody());
    }

    public function isResponseContainDataProvider()
    {
        return [
            ['rma/returns/addlabel', 'class="col carrier">CarrierTitle</td>'],
            ['rma/returns/dellabel', 'class="col carrier">CarrierTitle</td>']
        ];
    }
}
