<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftRegistry\Controller\Magento\Wishlist;

class IndexTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testIndexAction()
    {
        $this->markTestIncomplete('Bug MAGE-6447');
        $logger = $this->getMock('Psr\Log\LoggerInterface', [], [], '', false);
        $session = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Customer\Model\Session',
            [$logger]
        );
        /** @var \Magento\Customer\Api\AccountManagementInterface $service */
        $service = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Customer\Api\AccountManagementInterface'
        );
        $customer = $service->authenticate('customer@example.com', 'password');
        $session->setCustomerDataAsLoggedIn($customer);
        $this->dispatch('wishlist/index/index');
        $this->assertContains('id="giftregistry-form">', $this->getResponse()->getBody());
    }
}
