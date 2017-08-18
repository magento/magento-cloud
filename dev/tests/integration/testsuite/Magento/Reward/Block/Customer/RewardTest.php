<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Reward\Block\Customer;

class RewardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoAppArea frontend
     * @magentoDataFixture Magento/Reward/_files/history.php
     */
    public function testToHtml()
    {
        $customer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Customer\Model\Customer::class
        );
        $customer->load(1);

        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Customer\Model\Session::class
        )->setCustomer(
            $customer
        );

        $utility = new \Magento\Framework\View\Utility\Layout($this);
        $layout = $utility->getLayoutFromFixture(
            __DIR__ . '/../../_files/magento_reward_customer_info.xml',
            $utility->getLayoutDependencies()
        );

        $layout->getUpdate()->addHandle('magento_reward_customer_info')->load();
        $layout->generateXml()->generateElements();
        $layout->addOutputElement('customer.reward');

        $format = '%A<div class="block block-reward-info">%A<table class="data table table-reward-history">%A' .
            'class="form reward-settings"%A';
        $this->assertStringMatchesFormat($format, $layout->getOutput());
    }
}
