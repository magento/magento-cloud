<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
$defaultWebsiteId = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
    \Magento\Store\Model\StoreManagerInterface::class
)->getStore()->getWebsiteId();

/** @var $website \Magento\Store\Model\Website */
$website = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Store\Model\Website::class);
$website->setData(['code' => 'base2', 'name' => 'Test Website', 'default_group_id' => '1', 'is_default' => '0']);
$website->save();
\Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
    \Magento\Store\Model\StoreManagerInterface::class
)->reinitStores();

$additionalWebsiteId = $website->getId();

/** @var $objectManager \Magento\TestFramework\ObjectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$objectManager->get(\Magento\Framework\Registry::class)
    ->unregister('_fixture/Magento_ScheduledImportExport_Model_TestWebsite');
$objectManager->get(
    \Magento\Framework\Registry::class
)->register(
    '_fixture/Magento_ScheduledImportExport_Model_TestWebsite',
    $website
);

$expectedBalances = [];
$expectedRewards = [];

//Create customer
/** @var $customer \Magento\Customer\Model\Customer */
$customer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Customer::class
);
$customer->setWebsiteId(
    0
)->setEntityTypeId(
    1
)->setAttributeSetId(
    0
)->setEmail(
    'BetsyParker@example.com'
)->setPassword(
    'password'
)->setGroupId(
    1
)->setStoreId(
    1
)->setIsActive(
    1
)->setFirstname(
    'Betsy'
)->setLastname(
    'Parker'
)->setGender(
    2
);
$customer->isObjectNew(true);
$customer->save();

/** @var $customerBalance \Magento\CustomerBalance\Model\Balance */
$customerBalance = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerBalance\Model\Balance::class
);
$customerBalance->setCustomerId($customer->getId());
$customerBalance->setAmountDelta(50);
$customerBalance->setWebsiteId($additionalWebsiteId);
$customerBalance->save();

/** @var $rewardPoints \Magento\Reward\Model\Reward */
$rewardPoints = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Reward\Model\Reward::class
);
$rewardPoints->setCustomerId($customer->getId());
$rewardPoints->setPointsBalance(50);
$rewardPoints->setWebsiteId($additionalWebsiteId);
$rewardPoints->save();

$expectedBalances[$customer->getId()][$additionalWebsiteId] = 0;
$expectedRewards[$customer->getId()][$additionalWebsiteId] = 0;

/** @var $customer \Magento\Customer\Model\Customer */
$customer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Customer::class
);
$customer->setWebsiteId(
    0
)->setEntityTypeId(
    1
)->setAttributeSetId(
    0
)->setEmail(
    'AnthonyNealy@example.com'
)->setPassword(
    'password'
)->setGroupId(
    1
)->setStoreId(
    1
)->setIsActive(
    1
)->setFirstname(
    'Anthony'
)->setLastname(
    'Nealy'
)->setGender(
    1
);
$customer->isObjectNew(true);
$customer->save();

/** @var $customerBalance \Magento\CustomerBalance\Model\Balance */
$customerBalance = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerBalance\Model\Balance::class
);
$customerBalance->setCustomerId($customer->getId());
$customerBalance->setAmountDelta(100);
$customerBalance->setWebsiteId($defaultWebsiteId);
$customerBalance->save();

/** @var $rewardPoints \Magento\Reward\Model\Reward */
$rewardPoints = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Reward\Model\Reward::class
);
$rewardPoints->setCustomerId($customer->getId());
$rewardPoints->setPointsBalance(100);
$rewardPoints->setWebsiteId($defaultWebsiteId);
$rewardPoints->save();

$expectedBalances[$customer->getId()][$defaultWebsiteId] = 0;
$expectedRewards[$customer->getId()][$defaultWebsiteId] = 0;

/** @var $customer \Magento\Customer\Model\Customer */
$customer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Customer::class
);
$customer->setWebsiteId(
    0
)->setEntityTypeId(
    1
)->setAttributeSetId(
    0
)->setEmail(
    'LoriBanks@example.com'
)->setPassword(
    'password'
)->setGroupId(
    1
)->setStoreId(
    1
)->setIsActive(
    1
)->setFirstname(
    'Lori'
)->setLastname(
    'Banks'
)->setGender(
    2
);
$customer->isObjectNew(true);
$customer->save();

/** @var $customerBalance \Magento\CustomerBalance\Model\Balance */
$customerBalance = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerBalance\Model\Balance::class
);
$customerBalance->setCustomerId($customer->getId());
$customerBalance->setAmountDelta(200);
$customerBalance->setWebsiteId($additionalWebsiteId);
$customerBalance->save();

/** @var $rewardPoints \Magento\Reward\Model\Reward */
$rewardPoints = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Reward\Model\Reward::class
);
$rewardPoints->setCustomerId($customer->getId());
$rewardPoints->setPointsBalance(200);
$rewardPoints->setWebsiteId($additionalWebsiteId);
$rewardPoints->save();

$expectedBalances[$customer->getId()][$additionalWebsiteId] = 200;
$expectedRewards[$customer->getId()][$additionalWebsiteId] = 200;

/** @var $customerBalance \Magento\CustomerBalance\Model\Balance */
$customerBalance = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerBalance\Model\Balance::class
);
$customerBalance->setCustomerId($customer->getId());
$customerBalance->setAmountDelta(300);
$customerBalance->setWebsiteId($defaultWebsiteId);
$customerBalance->save();

/** @var $rewardPoints \Magento\Reward\Model\Reward */
$rewardPoints = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Reward\Model\Reward::class
);
$rewardPoints->setCustomerId($customer->getId());
$rewardPoints->setPointsBalance(300);
$rewardPoints->setWebsiteId($defaultWebsiteId);
$rewardPoints->save();

$expectedBalances[$customer->getId()][$defaultWebsiteId] = 300;
$expectedRewards[$customer->getId()][$defaultWebsiteId] = 300;

$customer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Customer::class
);
$customer->setWebsiteId(
    0
)->setEntityTypeId(
    1
)->setAttributeSetId(
    0
)->setEmail(
    'PatriciaPPerez@magento.com'
)->setPassword(
    'password'
)->setGroupId(
    1
)->setStoreId(
    1
)->setIsActive(
    1
)->setFirstname(
    'Patricia'
)->setLastname(
    'Perez'
)->setGender(
    2
);
$customer->isObjectNew(true);
$customer->save();

/** @var $customerBalance \Magento\CustomerBalance\Model\Balance */
$customerBalance = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerBalance\Model\Balance::class
);
$customerBalance->setCustomerId($customer->getId());
$customerBalance->setAmountDelta(400);
$customerBalance->setWebsiteId($additionalWebsiteId);
$customerBalance->save();

/** @var $rewardPoints \Magento\Reward\Model\Reward */
$rewardPoints = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Reward\Model\Reward::class
);
$rewardPoints->setCustomerId($customer->getId());
$rewardPoints->setPointsBalance(400);
$rewardPoints->setWebsiteId($additionalWebsiteId);
$rewardPoints->save();

$expectedBalances[$customer->getId()][$additionalWebsiteId] = 0;
$expectedRewards[$customer->getId()][$additionalWebsiteId] = 0;

/** @var $customerBalance \Magento\CustomerBalance\Model\Balance */
$customerBalance = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerBalance\Model\Balance::class
);
$customerBalance->setCustomerId($customer->getId());
$customerBalance->setAmountDelta(500);
$customerBalance->setWebsiteId($defaultWebsiteId);
$customerBalance->save();

/** @var $rewardPoints \Magento\Reward\Model\Reward */
$rewardPoints = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Reward\Model\Reward::class
);
$rewardPoints->setCustomerId($customer->getId());
$rewardPoints->setPointsBalance(500);
$rewardPoints->setWebsiteId($defaultWebsiteId);
$rewardPoints->save();

$expectedBalances[$customer->getId()][$defaultWebsiteId] = 500;
$expectedRewards[$customer->getId()][$defaultWebsiteId] = 500;

$objectManager->get(
    \Magento\Framework\Registry::class
)->unregister(
    '_fixture/Magento_ScheduledImportExport_Customers_ExpectedBalances'
);
$objectManager->get(
    \Magento\Framework\Registry::class
)->register(
    '_fixture/Magento_ScheduledImportExport_Customers_ExpectedBalances',
    $expectedBalances
);

$objectManager->get(
    \Magento\Framework\Registry::class
)->unregister(
    '_fixture/Magento_ScheduledImportExport_Customers_ExpectedRewards'
);
$objectManager->get(
    \Magento\Framework\Registry::class
)->register(
    '_fixture/Magento_ScheduledImportExport_Customers_ExpectedRewards',
    $expectedRewards
);
