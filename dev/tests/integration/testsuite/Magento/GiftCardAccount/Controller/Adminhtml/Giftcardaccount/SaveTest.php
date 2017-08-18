<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCardAccount\Controller\Adminhtml\Giftcardaccount;

class SaveTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoDataFixture Magento/Store/_files/core_fixturestore.php
     */
    public function testExecute()
    {
        $storeCode = 'fixturestore';
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var \Magento\Store\Api\StoreRepositoryInterface $storeRepository */
        $storeRepository = $objectManager->get(\Magento\Store\Api\StoreRepositoryInterface::class);
        /** @var \Magento\Store\Api\Data\StoreInterface $store */
        $store = $storeRepository->get($storeCode);
        $requestData = [
            'status' => '1',
            'is_redeemable' => '1',
            'website_id' => $store->getWebsiteId(),
            'balance' => '10',
            'date_expires' => '',
            'recipient_email' => 'poluyanovl@gmail.com',
            'recipient_name' => 'Leonid',
            'recipient_store' => $store->getId(),
            'send_action' => '1'
        ];
        /** @var \Magento\Framework\Message\ManagerInterface $messageManager */
        $messageManager = $objectManager->get(\Magento\Framework\Message\ManagerInterface::class);
        /** @var \Magento\GiftCardAccount\Controller\Adminhtml\Giftcardaccount\Save $model */
        $model = $objectManager->get(\Magento\GiftCardAccount\Controller\Adminhtml\Giftcardaccount\Save::class);

        $model->getRequest()->setPostValue($requestData);
        $objectManager->create(\Magento\GiftCardAccount\Model\Pool::class)->generatePool();
        $model->execute();
        $message = $messageManager->getMessages()->getLastAddedMessage()->getText();
        $this->assertEquals('You saved the gift card account.', $message);
    }
}
