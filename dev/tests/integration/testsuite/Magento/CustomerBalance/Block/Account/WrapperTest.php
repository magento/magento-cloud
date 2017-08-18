<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerBalance\Block\Account;

class WrapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoDataFixture Magento/CustomerBalance/_files/history.php
     */
    public function testToHtml()
    {
        \Magento\TestFramework\Helper\Bootstrap::getInstance()
            ->loadArea('frontend');
        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        /** @var \Magento\Customer\Model\Session $session */
        $session = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Customer\Model\Session::class,
            [$logger]
        );
        /** @var \Magento\Customer\Api\AccountManagementInterface $service */
        $service = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Customer\Api\AccountManagementInterface::class
        );
        $customer = $service->authenticate('customer@example.com', 'password');
        $session->setCustomerDataAsLoggedIn($customer);

        $utility = new \Magento\Framework\View\Utility\Layout($this);
        $layout = $utility->getLayoutFromFixture(
            __DIR__ . '/../../_files/magento_customerbalance_info_index.xml',
            $utility->getLayoutDependencies()
        );
        $layout->getUpdate()->addHandle('magento_customerbalance_info_index')->load();
        $layout->generateXml()->generateElements();
        $layout->addOutputElement('customerbalance.wrapper');
        $html = $layout->getOutput();

        $this->assertContains('<div class="storecredit">', $html);
        $format = '%A<div class="block block-balance">%A' .
            '<table id="customerbalance-history" class="data table table-balance-history">%A';
        $this->assertStringMatchesFormat($format, $html);
    }
}
