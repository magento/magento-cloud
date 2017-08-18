<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var $helper \Magento\ScheduledImportExport\Helper\Data */
$helper = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
    \Magento\ScheduledImportExport\Helper\Data::class
);

// customer with reward points and customer balance
/** @var $customer \Magento\Customer\Model\Customer */
$customer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Customer::class
);
$customer->addData(['firstname' => 'Test', 'lastname' => 'User']);
$customerEmail = 'customer_finance_test_rp_cb@test.com';
$registerKey = 'customer_finance_email_rp_cb';
/** @var $objectManager \Magento\TestFramework\ObjectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$objectManager->get(\Magento\Framework\Registry::class)->unregister($registerKey);
$objectManager->get(\Magento\Framework\Registry::class)->register($registerKey, $customerEmail);
$customer->setEmail($customerEmail);
$customer->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getStore()->getWebsiteId()
);
$customer->save();

/** @var $customerBalance \Magento\CustomerBalance\Model\Balance */
$customerBalance = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerBalance\Model\Balance::class
);
$customerBalance->setCustomerId($customer->getId());
$customerBalance->setAmountDelta(10);
$customerBalance->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getStore()->getWebsiteId()
);
$customerBalance->save();

/** @var $rewardPoints \Magento\Reward\Model\Reward */
$rewardPoints = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Reward\Model\Reward::class
);
$rewardPoints->setCustomerId($customer->getId());
$rewardPoints->setPointsBalance(20);
$rewardPoints->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getStore()->getWebsiteId()
);
$rewardPoints->save();

// customer with reward points and without customer balance
/** @var $customer \Magento\Customer\Model\Customer */
$customer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Customer::class
);
$customer->addData(['firstname' => 'Test', 'lastname' => 'User']);
$customerEmail = 'customer_finance_test_rp@test.com';
$registerKey = 'customer_finance_email_rp';
$objectManager->get(\Magento\Framework\Registry::class)->unregister($registerKey);
$objectManager->get(\Magento\Framework\Registry::class)->register($registerKey, $customerEmail);
$customer->setEmail($customerEmail);
$customer->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getStore()->getWebsiteId()
);
$customer->save();

/** @var $rewardPoints \Magento\Reward\Model\Reward */
$rewardPoints = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Reward\Model\Reward::class
);
$rewardPoints->setCustomerId($customer->getId());
$rewardPoints->setPointsBalance(20);
$rewardPoints->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getStore()->getWebsiteId()
);
$rewardPoints->save();

// customer without reward points and with customer balance
/** @var $customer \Magento\Customer\Model\Customer */
$customer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Customer::class
);
$customer->addData(['firstname' => 'Test', 'lastname' => 'User']);
$customerEmail = 'customer_finance_test_cb@test.com';
$registerKey = 'customer_finance_email_cb';
$objectManager->get(\Magento\Framework\Registry::class)->unregister($registerKey);
$objectManager->get(\Magento\Framework\Registry::class)->register($registerKey, $customerEmail);
$customer->setEmail($customerEmail);
$customer->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getStore()->getWebsiteId()
);
$customer->save();

/** @var $customerBalance \Magento\CustomerBalance\Model\Balance */
$customerBalance = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerBalance\Model\Balance::class
);
$customerBalance->setCustomerId($customer->getId());
$customerBalance->setAmountDelta(10);
$customerBalance->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getStore()->getWebsiteId()
);
$customerBalance->save();

// customer without reward points and customer balance
/** @var $customer \Magento\Customer\Model\Customer */
$customer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Customer::class
);
$customer->addData(['firstname' => 'Test', 'lastname' => 'User']);
$customerEmail = 'customer_finance_test@test.com';
$registerKey = 'customer_finance_email';
$objectManager->get(\Magento\Framework\Registry::class)->unregister($registerKey);
$objectManager->get(\Magento\Framework\Registry::class)->register($registerKey, $customerEmail);
$customer->setEmail($customerEmail);
$customer->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getStore()->getWebsiteId()
);
$customer->save();
