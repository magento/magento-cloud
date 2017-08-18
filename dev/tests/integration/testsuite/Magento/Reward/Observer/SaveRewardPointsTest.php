<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Reward\Observer;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class SaveRewardPointsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoDataFixture Magento/Customer/_files/import_export/customer.php
     * @dataProvider saveRewardPointsDataProvider
     *
     * @param integer $pointsDelta
     * @param integer $expectedBalance
     */
    public function testSaveRewardPoints($pointsDelta, $expectedBalance)
    {
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $objectManager->get(\Magento\Framework\Registry::class)
            ->registry('_fixture/Magento_ImportExport_Customer');

        /** @var CustomerRepositoryInterface $customerRepository */
        $customerRepository = $objectManager->get(\Magento\Customer\Api\CustomerRepositoryInterface::class);

        $this->_saveRewardPoints($customerRepository->getById($customer->getId()), $pointsDelta);

        /** @var $reward \Magento\Reward\Model\Reward */
        $reward = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Reward\Model\Reward::class
        );
        $reward->setCustomer($customer)->loadByCustomer();

        $this->assertEquals($expectedBalance, $reward->getPointsBalance());
    }

    public function saveRewardPointsDataProvider()
    {
        return [
            'points delta is not set' => ['$pointsDelta' => '', '$expectedBalance' => null],
            'points delta is positive' => ['$pointsDelta' => 100, '$expectedBalance' => 100]
        ];
    }

    /**
     * @param CustomerInterface $customer
     * @param mixed $pointsDelta
     */
    protected function _saveRewardPoints(CustomerInterface $customer, $pointsDelta = '')
    {
        $reward = ['points_delta' => $pointsDelta];

        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var $request \Magento\TestFramework\Request */
        $request = $objectManager->get(\Magento\TestFramework\Request::class);
        $request->setPostValue(['reward' => $reward]);

        $event = new \Magento\Framework\Event(['request' => $request, 'customer' => $customer]);

        $eventObserver = new \Magento\Framework\Event\Observer(['event' => $event]);

        $rewardObserver = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Reward\Observer\SaveRewardPoints::class
        );
        $rewardObserver->execute($eventObserver);
    }
}
