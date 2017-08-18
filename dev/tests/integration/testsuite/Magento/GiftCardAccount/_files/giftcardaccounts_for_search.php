<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\GiftCardAccount\Model\Giftcardaccount;
use Magento\Store\Model\StoreManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;

$accounts = [
    [
        'code' => 'gift_card_account_1',
        'status' => Giftcardaccount::STATUS_ENABLED,
        'state' => Giftcardaccount::STATE_USED,
        'is_redeemable' => Giftcardaccount::REDEEMABLE,
        'balance' => 10,
    ],
    [
        'code' => 'gift_card_account_2',
        'status' => Giftcardaccount::STATUS_ENABLED,
        'state' => Giftcardaccount::STATE_AVAILABLE,
        'is_redeemable' => Giftcardaccount::REDEEMABLE,
        'balance' => 20,
    ],
    [
        'code' => 'gift_card_account_3',
        'status' => Giftcardaccount::STATUS_ENABLED,
        'state' => Giftcardaccount::STATE_USED,
        'is_redeemable' => Giftcardaccount::REDEEMABLE,
        'balance' => 30,
    ],
    [
        'code' => 'gift_card_account_4',
        'status' => Giftcardaccount::STATUS_ENABLED,
        'state' => Giftcardaccount::STATE_REDEEMED,
        'is_redeemable' => Giftcardaccount::REDEEMABLE,
        'balance' => 40,
    ],
    [
        'code' => 'gift_card_account_5',
        'status' => Giftcardaccount::STATUS_ENABLED,
        'state' => Giftcardaccount::STATE_AVAILABLE,
        'is_redeemable' => Giftcardaccount::NOT_REDEEMABLE,
        'balance' => 50,
    ],
];

foreach ($accounts as $data) {
    /** @var $model Giftcardaccount */
    $model = Bootstrap::getObjectManager()->create(Giftcardaccount::class);
    $model->setCode($data['code'])
        ->setStatus($data['status'])
        ->setState($data['state'])
        ->setWebsiteId(Bootstrap::getObjectManager()->get(StoreManagerInterface::class)->getWebsite()->getId())
        ->setIsRedeemable($data['is_redeemable'])
        ->setBalance($data['balance'])
        ->setDateExpires(date('Y-m-d', strtotime('+1 week')))
        ->save();
}
