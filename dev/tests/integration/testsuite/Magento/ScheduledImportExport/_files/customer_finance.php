<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// add new website
/** @var $website \Magento\Store\Model\Website */
$website = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Store\Model\Website');
$website->setCode('finance_website')->setName('Finance Website');
$website->save();
\Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
    'Magento\Store\Model\StoreManagerInterface'
)->reinitStores();

// create test customer
/** @var $customer \Magento\Customer\Model\Customer */
$customer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Customer\Model\Customer');
$customer->addData(['firstname' => 'Test', 'lastname' => 'User']);
$customerEmail = 'customer_finance_test@test.com';
$registerKey = 'customer_finance_email';
/** @var $objectManager \Magento\TestFramework\ObjectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$objectManager->get('Magento\Framework\Registry')->unregister($registerKey);
$objectManager->get('Magento\Framework\Registry')->register($registerKey, $customerEmail);
$customer->setEmail($customerEmail);
$customer->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        'Magento\Store\Model\StoreManagerInterface'
    )->getStore()->getWebsiteId()
);
$customer->save();

// create store credit and reward points
/** @var $helper \Magento\ScheduledImportExport\Helper\Data */
$helper = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
    'Magento\ScheduledImportExport\Helper\Data'
);

// increment to modify balance values
$increment = 0;
$websites = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
    'Magento\Store\Model\StoreManagerInterface'
)->getWebsites();
/** @var $website \Magento\Store\Model\Website */
foreach ($websites as $website) {
    $increment += 10;

    /** @var $customerBalance \Magento\CustomerBalance\Model\Balance */
    $customerBalance = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
        'Magento\CustomerBalance\Model\Balance'
    );
    $customerBalance->setCustomerId($customer->getId());
    $customerBalanceAmount = 50 + $increment;
    $registerKey = 'customer_balance_' . $website->getCode();
    $objectManager->get('Magento\Framework\Registry')->unregister($registerKey);
    $objectManager->get('Magento\Framework\Registry')->register($registerKey, $customerBalanceAmount);
    $customerBalance->setAmountDelta($customerBalanceAmount);
    $customerBalance->setWebsiteId($website->getId());
    $customerBalance->save();

    /** @var $rewardPoints \Magento\Reward\Model\Reward */
    $rewardPoints = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Reward\Model\Reward');
    $rewardPoints->setCustomerId($customer->getId());
    $rewardPointsBalance = 100 + $increment;
    $registerKey = 'reward_point_balance_' . $website->getCode();
    $objectManager->get('Magento\Framework\Registry')->unregister($registerKey);
    $objectManager->get('Magento\Framework\Registry')->register($registerKey, $rewardPointsBalance);
    $rewardPoints->setPointsBalance($rewardPointsBalance);
    $rewardPoints->setWebsiteId($website->getId());
    $rewardPoints->save();
}
