<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
require 'creditmemo_with_gift_card_account.php';

use Magento\GiftCardAccount\Api\GiftCardAccountRepositoryInterface;
use Magento\GiftCardAccount\Model\Giftcardaccount;
use Magento\TestFramework\Helper\Bootstrap;

$giftcardaccountRepository = Bootstrap::getObjectManager()->get(GiftCardAccountRepositoryInterface::class);

$giftcardAccount = $objectManager->create(Giftcardaccount::class);
$giftcardAccount2 = $objectManager->create(Giftcardaccount::class);

$giftcardAccount->loadByCode('TESTCODE1');
$giftcardAccount2->loadByCode('TESTCODE2');

$giftcardaccountRepository->delete($giftcardAccount);
$giftcardaccountRepository->delete($giftcardAccount2);
